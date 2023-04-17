<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Task;
use App\Rules\BlacklistedTaskRule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class TaskController extends Controller
{
    public function index()
    {
        return Inertia::render('Task/Success');
    }

    public function create()
    {
        return Inertia::render('Task/Create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'country_id' => ['required', 'numeric'],
            'brand' => ['required', 'string'],
            'website' => ['required', 'string'],
            'submitter_credits' => ['required', 'numeric'],
            'executor_credits' => ['required', 'numeric'],
            'task' => ['required', 'string', new BlacklistedTaskRule, Rule::unique('tasks', 'key')],
            'summary' => ['required', 'string'],
        ], [
            'brand.unique' => 'The :attribute name already exists.',
            'task.unique' => 'The :attribute already exists.'
        ]);

        //create brand
        $brand = Brand::where('name', $request->brand)->first();

        if (!$brand) {
            $brand = Brand::create(['name' => $request->brand]);
        }

        try {
            //create task
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
        } catch (\Throwable $th) {
            return redirect()->back()->withErrors(['error' => 'Something went wrong. Please try again.']);
        }
    }

    public function show($id)
    {
        $task = Task::with(['submitter', 'brand'])->find($id);

        return Inertia::render('Task/Show', [
            'task' => $task,
        ]);
    }

    public function fulfill($task_id)
    {
        $task = Task::with('brand')->findOrFail($task_id);

        $task->update([
            'executor_id' => Auth::id(),
            'status' => 'PENDING_VERIFICATION',
            'fulfilled_at' => Carbon::now()
        ]);

        return Inertia::render('Task/Fulfill', [
            'task' => $task,
        ]);
    }

    public function complete(Request $request)
    {
        $validatedData = $request->validate([
            'key' => ['required', 'string'],
            'task_id' => ['required', 'numeric'],
        ]);

        $parent_id = $validatedData['task_id'];
        $task = Task::findOrFail($parent_id);

        try {
            $newTask = Task::create([
                'parent_id' => $parent_id, // to access the parent_id in Model's booted methods (being unset there)
                'key' => $validatedData['key'],
                'brand_id' => $task->brand_id,
                'country_id' => $task->country_id,
                'submitter_id' => Auth::id(),
                'website' => $task->website,
                'summary' => $task->summary,
                'submitter_credits' => $task->submitter_credits,
                'executor_credits' => $task->executor_credits,
            ]);

            return redirect()->route('task.created', $newTask->id);
        } catch (\Throwable $th) {
            return redirect()->route('home');
        }
    }

    public function created($id)
    {
        $task = Task::with(['brand'])->findOrFail($id);

        return Inertia::render('Task/Success', [
            'task' => $task,
        ]);
    }

    public function updateStatus(Request $request)
    {
        $validatedData = $request->validate([
            'task_id' => ['required', 'numeric'],
            'status' => ['required', 'string'],
        ]);

        $task = Task::findOrFail($validatedData['task_id']);

        if ($validatedData['status'] == 'DISPUTED') {
            if ($task->fulfilled_at && !Carbon::parse($task->fulfilled_at)->diffInDays(Carbon::now()) >= 15) {
                return redirect()->route('profile.detail');
            }
        }

        $task->update([
            'status' => $validatedData['status'],
        ]);
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
