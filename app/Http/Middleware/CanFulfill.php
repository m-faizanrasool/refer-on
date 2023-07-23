<?php

namespace App\Http\Middleware;

use App\Models\Task;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class CanFulfill
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $taskId = $request->route('task_id');
        $task = Task::find($taskId);

        if (!$task || ($task->executor_id && $task->executor_id !== auth()->id())) {
            return Redirect::route('home')->with('message', 'Unauthorized');
        }

        return $next($request);
    }
}
