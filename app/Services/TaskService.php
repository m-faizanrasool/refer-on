<?php

namespace App\Services;

use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\HttpException;

class TaskService
{
    public static function search($query, $country_id = null)
    {
        if(!$country_id) {
            $country_id = Auth::user()->country_id;
        }

        $task = Task::with(['submitter', 'brand'])
            ->where(['country_id' => $country_id, 'status' => 'AVAILABLE'])
            ->where(function ($q) use ($query) {
                $q->whereHas('brand', function ($brand) use ($query) {
                    $brand->where('name', 'like', '%'.$query.'%');
                })
                ->orWhere('website', 'like', '%'.$query.'%');
            })
            ->first();

        return $task;
    }

    public static function complete($id, $executor_id = null)
    {
        $task = Task::find($id);
        if(!$task) {
            throw new HttpException(404, "Task not found");
        }

        if (!$executor_id) {
            $executor_id = Auth::id();
        }

        $task->executor_id = $executor_id;
        $task->status = "PENDING_VERIFICATION";
        $task->save();
    }

    public static function resetToAvailable($id)
    {
        $task = Task::find($id);
        if(!$task) {
            throw new HttpException(404, "Task not found");
        }

        $task->executor_id = null;
        $task->status = "AVAILABLE";
        $task->save();
    }
}
