<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TaskService;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('search');

        $availableTasks = [];

        if (!empty($query) && Auth::user()) {
            $availableTasks = TaskService::search($query);
        }

        return Inertia::render('Home', [
            'availableTasks' => $availableTasks,
            'quereyParam' => $query,
        ]);
    }
}
