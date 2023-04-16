<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('search');

        $availableTasks = [];

        if (!empty($query) && Auth::user()) {
            $tasks = Task::with(['submitter', 'brand'])
                ->where([
                    'country_id' => Auth::user()->country_id,
                    'status' => 'AVAILABLE'
                ])
                ->where(function ($q) use ($query) {
                    $q->whereHas('brand', function ($brand) use ($query) {
                        $brand->where('name', 'Like', '%'.$query.'%');
                    })
                    ->orWhere('website', 'like', '%'.$query.'%');
                })
                ->get();

            $brandIds = [];

            foreach ($tasks as $task) {
                if (!in_array($task->brand_id, $brandIds)) {
                    $availableTasks[] = [
                        'id' => $task->id,
                        'brand' => $task->brand->name
                    ];
                    $brandIds[] = $task->brand_id;
                }
            }
        }

        return Inertia::render('Home', [
            'availableTasks' => $availableTasks,
            'quereyParam' => $query
        ]);
    }
}
