<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Task;
use App\Providers\RouteServiceProvider;
use App\Rules\BrandUniqueRule;
use App\Rules\UniqueKeyRule;
use App\Rules\ValidUrl;
use App\Services\TaskService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            'brand' => ['required', 'string', new BrandUniqueRule($request->country_id)],
            'website' => ['required', 'string', new ValidUrl],
            'submitter_credits' => ['required', 'numeric'],
            'executor_credits' => ['required', 'numeric'],
            'code' => ['required', 'string'],
            'summary' => ['required', 'string'],
        ], [
            'brand.unique' => 'The :attribute name already exists.',
        ]);

        // create brand
        $brand = Brand::create([
            'name' => $validatedData['brand'],
            'country_id' => $validatedData['country_id'],
            'website' => $validatedData['website'],
            'summary' => $validatedData['summary'],
            'submitter_credits' => $validatedData['submitter_credits'],
            'executor_credits' => $validatedData['executor_credits'],
        ]);

        try {
            TaskService::validate($brand->id, $validatedData['code']);
        } catch (\Throwable $th) {
            $brand->delete();
            return redirect()->route('task.create')->with('error', $th->getMessage());
        }

        $task = Task::create([
            'code' => $validatedData['code'],
            'brand_id' => $brand->id,
            'submitter_id' => Auth::id(),
        ]);

        return redirect()->route('task.created', $task->id);
    }

    public function show($id)
    {
        $task = Task::with(['submitter', 'brand'])->findOrFail($id);

        $alreadyExists = Task::where('brand_id', $task->brand_id)
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

        if ($task->submitter_id === $authId) {
            return redirect()->route('home')->with('error', 'You cannot fulfill your own task');
        }

        if (($task->executor_id && $task->executor_id !== $authId) && $task->status != 'AVAILABLE') {
            try {
                TaskService::validate($task->brand_id);
                // create a new task as a sibling for concurrent user to complete
                $task = Task::create([
                    'sibling_id' => $task->id,
                    'code' => $task->code,
                    'brand_id' => $task->brand_id,
                    'submitter_id' => $task->submitter_id,
                    'executor_id' => $authId,
                    'status' => 'PENDING_VERIFICATION',
                    'fulfilled_at' => Carbon::now()
                ]);

                return inertia('Task/Fulfill', compact('task'));
            } catch (\Throwable $e) {
                return redirect()->route('home')->with('error', $e->getMessage());
            }
        }

        try {
            if (!$task->executor_id && !session('error')) {
                TaskService::validate($task->brand_id);

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
            'code' => ['required', 'string', new UniqueKeyRule],
            'task_id' => ['required', 'numeric'],
        ]);

        $parentTask = Task::findOrFail($validatedData['task_id']);

        try {
            TaskService::validate($parentTask->brand_id, $validatedData['code'], $parentTask->id);

            $newTask = Task::create([
                'code' => $validatedData['code'],
                'brand_id' => $parentTask->brand_id,
                'parent_id' => $parentTask->id,
                'submitter_id' => Auth::id(),
            ]);

            return redirect()->route('task.created', $newTask->id);
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
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

            if ($validatedData['status'] === 'DISPUTED') {
                TaskService::addDemeritPoint($task->executor);
            }

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
                    throw new \InvalidArgumentException('Wait 15 days before checking with brands to process referral codes, and submit a dispute if needed.');
                }
                break;
        }
    }

    public function destroy($id)
    {
        $task = Task::findOrFail($id);

        if ($task->submitter_id === Auth::id() && $task->status === 'AVAILABLE') {
            $task->delete();
            return redirect()->route('task.index');
        }

        return redirect()->route('task.index')->with('error', 'Cannot delete task.');
    }
}
