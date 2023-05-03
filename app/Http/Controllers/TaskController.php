<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Task;
use App\Providers\RouteServiceProvider;
use App\Rules\BlacklistedTaskRule;
use App\Services\TaskService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class TaskController extends Controller
{
    public function index(): \Inertia\Response
    {
        $tasks =   Task::with('childs')
        ->where(function ($query) {
            $query->where('submitter_id', Auth::id())
                  ->orWhere('executor_id', Auth::id());
        })
        ->latest()
        ->paginate(10);

        return inertia('Task/Index', compact('tasks'));
    }

    public function create()
    {
        return inertia('Task/Create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'country_id' => ['required', 'numeric'],
            'brand' => ['required', 'string'],
            'website' => ['required', 'string'],
            'submitter_credits' => ['required', 'numeric'],
            'executor_credits' => ['required', 'numeric'],
            'task' => ['required', 'string', Rule::unique('tasks', 'key')],
            'summary' => ['required', 'string'],
        ], [
            'brand.unique' => 'The :attribute name already exists.',
            'task.unique' => 'This code already exists.'
        ]);

        //create brand
        $brand = Brand::firstOrCreate(['name' => $request->brand]);

        try {
            TaskService::validate($validatedData['country_id'], $brand->id, $validatedData['task']);
        } catch (\Throwable $th) {
            return redirect()->route('task.create')->with('error', $th->getMessage());
        }

        $task = Task::create([
            'key' => $validatedData['task'],
            'brand_id' => $brand->id,
            'country_id' => $validatedData['country_id'],
            'submitter_id' => Auth::id(),
            'website' => $validatedData['website'],
            'summary' => $validatedData['summary'],
            'submitter_credits' => $validatedData['submitter_credits'],
            'executor_credits' => $validatedData['executor_credits'],
        ]);

        return redirect()->route('task.created', $task->id);
    }

    public function show($id)
    {
        $task = Task::with(['submitter', 'brand'])->findOrFail($id);

        $alreadyExists = Task::where('country_id', $task->country_id)
        ->where('brand_id', $task->brand_id)
        ->whereNotIn('status', ['INVALID', 'BLACKLISTED'])
        ->where(function ($query) {
            $query->where('submitter_id', Auth::id())
                  ->orWhere('executor_id', Auth::id());
        })
        ->exists();

        return inertia('Task/Show', compact('task', 'alreadyExists'));
    }

    public function fulfill($taskId)
    {
        $task = Task::with('brand')->findOrFail($taskId);

        $authId = Auth::id();

        if ($task->executor_id && $task->executor_id !== $authId || count($task->childs) > 1) {
            return redirect()->route('home')->with('error', 'Task already fulfilled');
        }

        if ($task->submitter_id === $authId) {
            return redirect()->route('home')->with('error', 'You cannot fulfill your own task');
        }

        try {
            if (!$task->executor_id) {
                TaskService::validate($task->country_id, $task->brand_id);

                $task->update([
                    'executor_id' => $authId,
                    'status' => 'PENDING_VERIFICATION',
                    'fulfilled_at' => Carbon::now()
                ]);
            }

            return inertia('Task/Fulfill', compact('task'));
        } catch (\Throwable $e) {
            return redirect()->route('home')->with('error', $e->getMessage());
        }
    }

    public function invalid(Request $request)
    {
        $validatedData = $request->validate([
            'task_id' => ['required', 'numeric'],
        ]);

        TaskService::reportInvalid($validatedData['task_id'], Auth::user()->id);

        return redirect(RouteServiceProvider::HOME)->with('message', 'Task Reported Successfully');
    }

    public function complete(Request $request)
    {
        $validatedData = $request->validate([
            'key' => ['required', 'string', Rule::unique('tasks')],
            'task_id' => ['required', 'numeric'],
        ]);

        $task = Task::findOrFail($validatedData['task_id']);

        try {
            TaskService::validate($task->country_id, $task->brand_id, $validatedData['key'], $task->id);

            $newTask = Task::create([
                'key' => $validatedData['key'],
                'brand_id' => $task->brand_id,
                'country_id' => $task->country_id,
                'parent_id' => $task->id,
                'submitter_id' => Auth::id(),
                'website' => $task->website,
                'summary' => $task->summary,
                'submitter_credits' => $task->submitter_credits,
                'executor_credits' => $task->executor_credits,
            ]);

            return redirect()->route('task.created', $newTask->id);
        } catch (\Throwable $e) {
            return redirect()->route('home')->with('error', $e->getMessage());
        }
    }

    public function created($taskId)
    {
        $task = Task::with(['brand'])->findOrFail($taskId);

        return Inertia::render('Task/Success', [
            'task' => $task,
        ]);
    }


    public function updateStatus(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'status' => ['required', 'string'],
                'task_id' => ['required', 'numeric'],
            ]);

            $task = Task::findOrFail($validatedData['task_id']);

            $this->checkTaskStatus($task, $validatedData['status']);

            $task->update([
                'status' => $validatedData['status'],
            ]);

            return redirect()->route('profile.detail')->with('success', 'Task status updated successfully.');
        } catch (\Throwable $e) {
            return redirect()->route('profile.detail')->with('error', $e->getMessage());
        }
    }

    private function checkTaskStatus(Task $task, string $status)
    {
        switch ($status) {
            case 'DISPUTED':
                if ($task->fulfilled_at && (Carbon::parse($task->fulfilled_at)->diffInDays(Carbon::now()) < 15)) {
                    throw new \InvalidArgumentException('Task must be open for at least 15 days before it can be disputed.');
                }
                break;
        }
    }

    public function edit($id)
    {
        $task = Task::with('brand')->find($id);

        if ($task->submitter_id !== Auth::id()) {
            return redirect()->route('task.index')->with('error', 'Cannot update someone else task.');
        }

        return Inertia::render('Task/Edit', [
             'task' => $task,
        ]);
    }

    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);

        if ($task->status !== 'AVAILABLE') {
            return redirect()->route('task.index')->with('error', 'Only Non-Executed tasks can be updated.');
        }

        $validatedData = $request->validate([
            'country_id' => ['required', 'numeric'],
            'brand' => ['required', 'string'],
            'website' => ['required', 'string'],
            'submitter_credits' => ['required', 'numeric'],
            'executor_credits' => ['required', 'numeric'],
            'task' => ['required', 'string', new BlacklistedTaskRule, Rule::unique('tasks', 'key')->ignore($id)],
            'summary' => ['required', 'string'],
        ], [
            'task.unique' => 'The :attribute already exists.'
        ]);

        $brand = Brand::where('name', $request->brand)->first();

        if (!$brand) {
            $brand = Brand::create(['name' => $request->brand]);
        }

        $task->update([
            'key' => $validatedData['task'],
            'brand_id' => $brand->id,
            'country_id' => $validatedData['country_id'],
            'submitter_id' => Auth::id(),
            'website' => $validatedData['website'],
            'summary' => $validatedData['summary'],
            'submitter_credits' => $validatedData['submitter_credits'],
            'executor_credits' => $validatedData['executor_credits'],
        ]);

        return redirect()->route('task.index');
    }

    public function destroy($id)
    {
        $task = Task::findOrFail($id);

        if ($task->submitter_id === Auth::id() && $task->status === 'AVAILABLE') {
            $task->delete();
            return redirect()->route('task.index');
        }

        return redirect()->route('task.index')->with('error', 'Cannot delete task');
    }
}
