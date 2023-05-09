<?php

namespace App\Services;

use App\Models\BlacklistedTasks;
use App\Models\Brand;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class TaskService
{
    public static function search($query, $country_id = null)
    {
        $country_id = $country_id ?? Auth::user()->country_id;

        $tasks = Task::with(['submitter', 'brand'])
            ->where('status', 'AVAILABLE')
            ->where(function ($q) use ($query, $country_id) {
                $q->whereHas('brand', function ($brand) use ($query, $country_id) {
                    $brand->where('name', 'Like', '%'.$query.'%')
                    ->where('country_id', $country_id);
                });
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

    private static function addDemeritPoint($submitter)
    {
        $submitter->demerit_points += 1;

        if ($submitter->demerit_points % 3 === 0) {
            $submitter->status = 'BLOCKED';
            $submitter->blocked_until = Carbon::now()->addDays(90);
        }

        $submitter->save();
    }

    public static function reportInvalid(int $id, ?int $executor_id = null)
    {
        $task = Task::findOrFail($id);

        $task->fill([
            'executor_id' => $executor_id ?? Auth::id(),
            'status' => 'INVALID',
        ])->save();

        self::addDemeritPoint($task->submitter);
    }

    public static function isDuplicateTask(int $brand_id): void
    {
        $task = Task::where('brand_id', $brand_id)
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

    public static function isBlacklisted(string $code, int $brand_id): bool
    {
        return BlacklistedTasks::where([
            'code' => $code,
            'brand_key' => Brand::find($brand_id)->key,
            'country_id' => Auth::user()->country_id,
        ])->exists();
    }

    /**
         * @param  \App\Models\Task  $task
         */
    public static function validate($brand_id, $code = null, $parent_id = null)
    {
        try {
            self::isDuplicateTask($brand_id);

            if ($code) {
                $isblacklistedTask = self::isBlacklisted($code, $brand_id);

                if ($isblacklistedTask && $parent_id) {
                    $task = Task::findOrFail($parent_id);

                    $failedTask = Task::create([
                        'code' => $code,
                        'brand_id' => $task->brand_id,
                        'submitter_id' => Auth::id(),
                        'status' => 'BLACKLISTED',
                    ]);

                    self::addDemeritPoint($failedTask->submitter);
                }

                if ($isblacklistedTask) {
                    throw new \Exception('This task is blacklisted and cannot be submitted.');
                }
            }
        } catch (\Exception $exception) {
            if ($parent_id) {
                self::resetToAvailable($parent_id);
            }

            throw $exception;
        }
    }
}
