<?php

namespace App\Services;

use App\Models\BlacklistedTasks;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class TaskService
{
    public static function search($query, $country_id = null)
    {
        $country_id = $country_id ?? Auth::user()->country_id;

        $tasks = Task::with(['submitter', 'brand'])
            ->where('country_id', $country_id)
            ->where('status', 'AVAILABLE')
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

    public static function resetToAvailable(int $id): void
    {
        $task = Task::findOrFail($id);

        $task->fill([
            'executor_id' => null,
            'fulfilled_at' => null,
            'status' => 'AVAILABLE',
        ])->save();
    }



    public static function reportInvalid(int $id, ?int $executor_id = null)
    {
        $task = Task::findOrFail($id);

        $task->fill([
            'executor_id' => $executor_id ?? Auth::id(),
            'status' => 'INVALID',
        ])->save();

        $submitter = $task->submitter;
        $submitter->demerit_points += 1;

        if ($submitter->demerit_points % 3 === 0) {
            $submitter->fill([
                'status' => 'BLOCKED',
                'blocked_until' => Carbon::now()->addDays(90),
            ])->save();

            Auth::logout();

            return redirect('/');
        } else {
            $submitter->save();
        }
    }

    public static function isDuplicateTask(int $country_id, int $brand_id): void
    {
        $task = Task::where('country_id', $country_id)
            ->where('brand_id', $brand_id)
            ->whereNotIn('status', ['INVALID', 'BLACKLISTED'])
            ->where(function ($query) {
                $query->where('submitter_id', Auth::id())
                    ->orWhere('executor_id', Auth::id());
            })
            ->get();

        if ($task->count() > 1) {
            throw new \Exception('You can only submit or complete one task for ' . $task->first()->brand->name . ' in ' . $task->first()->country->name . '.');
        }
    }

    public static function isBlacklisted(string $key, int $brand_id): bool
    {
        $isblacklistedTask = BlacklistedTasks::where([
            'key' => $key,
            'brand_id' => $brand_id,
            'country_id' => Auth::user()->country_id,
        ])->exists();

        return $isblacklistedTask;
    }

    /**
         * @param  \App\Models\Task  $task
         */
    public static function validate($country_id, $brand_id, $key = null, $task_id = null)
    {
        try {
            self::isDuplicateTask($country_id, $brand_id);

            if ($key) {
                $isblacklistedTask = self::isBlacklisted($key, $brand_id);

                if ($isblacklistedTask && $task_id) {
                    $task = Task::findOrFail($task_id);

                    $failedTask = Task::create([
                        'key' => $key,
                        'brand_id' => $task->brand_id,
                        'country_id' => $task->country_id,
                        'submitter_id' => Auth::id(),
                        'website' => $task->website,
                        'summary' => $task->summary,
                        'submitter_credits' => 0,
                        'executor_credits' => 0,
                        'status' => 'BLACKLISTED',
                    ]);

                    $submitter = $failedTask->submitter;
                    $submitter->demerit_points += 1;

                    if ($submitter->demerit_points % 3 === 0) {
                        $submitter->fill([
                            'status' => 'BLOCKED',
                            'blocked_until' => Carbon::now()->addDays(90),
                        ])->save();

                        Auth::logout();

                        return redirect('/');
                    } else {
                        $submitter->save();
                    }
                }

                if ($isblacklistedTask) {
                    throw new \Exception('This task is blacklisted and cannot be submitted.');
                }
            }
        } catch (\Exception $exception) {
            if ($task_id) {
                self::resetToAvailable($task_id);
            }

            throw $exception;
        }
    }
}
