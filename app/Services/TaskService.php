<?php

namespace App\Services;

use App\Models\BlacklistedTasks;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class TaskService
{
    public static function search($query, $country_id = null)
    {
        $country_id = $country_id ?? Auth::user()->country_id;

        $tasks = Task::with(['submitter', 'brand'])
            ->where('country_id', $country_id)
            ->where('status', 'AVAILABLE')
            ->where('submitter_id', '<>', Auth::id())
            ->where(function ($q) use ($query) {
                $q->whereHas('brand', function ($brand) use ($query) {
                    $brand->where('name', 'Like', '%'.$query.'%');
                })
                ->orWhere('website', 'like', '%'.$query.'%');
            })
            ->get();

        $availableTasks = [];
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

        return $availableTasks;
    }

    public static function resetToAvailable($id)
    {
        $task = Task::findOrFail($id);

        $task->executor_id = null;
        $task->fulfilled_at = null;
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
            $submitter->blocked_until = Carbon::now()->addDays(90);
        }

        $submitter->save();

        return $task;
    }

    /**
     * @param  \App\Models\Task  $task
     */
    public static function validate($task_id, $key = null)
    {
        try {
            $task = Task::findOrFail($task_id);

            // Get count of tasks for this brand and country submitted or executed by the authenticated user
            $count = Task::where('country_id', $task->country_id)
                ->where('brand_id', $task->brand_id)
                ->where(function ($query) {
                    $query->where('submitter_id', Auth::id())
                          ->orWhere('executor_id', Auth::id());
                })
                ->count();

            if ($count > 1) {
                throw new \Exception('You can only submit or complete one task for '.$task->brand->name.' in '.$task->country->name.'.');
            }

            if ($key) {
                // Check that the task is not on the blacklist
                $blacklistedTask = BlacklistedTasks::where('key', $key)
                    ->where('country_id', $task->country_id)
                    ->where('brand_id', $task->brand_id)
                    ->exists();

                if ($blacklistedTask) {
                    $failedTask = Task::create([
                        'key' => $key,
                        'brand_id' => $task->brand_id,
                        'country_id' => $task->country_id,
                        'submitter_id' => Auth::id(),
                        'website' => $task->website,
                        'summary' => $task->summary,
                        'submitter_credits' => 0,
                        'executor_credits' => 0,
                        'status' => 'INVALID',
                    ]);

                    $submitter = $failedTask->submitter;
                    $submitter->demerit_points += 1;

                    if ($submitter->demerit_points % 3 == 0) {
                        $submitter->status = "BLOCKED";
                        $submitter->blocked_until = Carbon::now()->addDays(90);
                    }

                    $submitter->save();

                    throw new \Exception('This task is blacklisted and cannot be submitted');
                }
            }
        } catch (\Exception $exception) {
            if ($key) {
                self::resetToAvailable($task_id);
            }

            throw $exception;
        }
    }
}
