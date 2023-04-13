<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Task;
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
            'brand' => ['required', 'string', Rule::unique('brands', 'name')],
            'website' => ['required', 'string'],
            'submitter_credits' => ['required', 'numeric'],
            'executor_credits' => ['required', 'numeric'],
            'task' => ['required', 'string', Rule::unique('tasks', 'key')],
            'summary' => ['required', 'string'],
        ], [
            'brand.unique' => 'The :attribute name already exists.',
        ]);

        $validatedData['key'] = strtolower(str_replace(' ', '-', $validatedData['brand']));

        //create brand
        $brand = Brand::create([
            'key' =>  $validatedData['key'],
            'name' => $validatedData['brand'],
        ]);

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

        $task = Task::findOrFail($validatedData['task_id']);

        //create new task
        $newTask = Task::create([
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
    }

    public function created($id)
    {
        $task = Task::with(['brand'])->findOrFail($id);

        return Inertia::render('Task/Success', [
            'task' => $task,
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
