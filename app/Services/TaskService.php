<?php

namespace App\Services;

use App\Models\BlacklistedTasks;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\HttpException;

class TaskService
{
    public static function search($query, $country_id = null)
    {
        if (!$country_id) {
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
        $task = Task::findOrFail($id);

        $task->executor_id = $executor_id ?? Auth::id();
        $task->status = "PENDING_VERIFICATION";
        $task->save();
    }

    public static function resetToAvailable($id)
    {
        $task = Task::findOrFail($id);

        $task->executor_id = null;
        $task->status = "AVAILABLE";
        $task->save();
    }

    public static function reportInvalid($id, $executor_id = null)
    {
        $task = Task::findOrFail($id);

        $task->status = "INVALID";
        $task->executor_id = $executor_id ?? Auth::id();
        $task->save();

        $submitter = $task->submitter;
        $submitter->demerit_points += 1;

        if ($submitter->demerit_points % 3 == 0) {
            $submitter->status = "BLOCKED";
            $submitter->blocked_until = Carbon::now();
        }

        $submitter->save();

        return $task;
    }

    /**
     * @param  \App\Models\Task  $task
     */
    public static function validate($task, $executor_id = null)
    {
        try {
            if (BlacklistedTasks::where('key', $task->key)->exists()) {
                throw new HttpException(409, $task->key . " is BlackListed");
            }

            $user = User::findOrFail($executor_id ?? Auth::id());

            $task_exists = Task::where(["country_id" => $user->country_id, "brand_id" => $task->brand_id])
            ->where(function ($q) use ($user) {
                $q->where('executor_id', $user->id)
                    ->orWhere('submitter_id', $user->id);
            })
            ->exists();

            if ($task_exists) {
                throw new HttpException(409, "You already have a task for " . $user->country->name . " - " . $task->brand->name);
            }
        } catch (HttpException $exception) {
            if (!empty($task->parent_id)) {
                self::resetToAvailable($task->parent_id);
                // TODO: self::createLog();
            }

            throw $exception;
        }
    }
}
