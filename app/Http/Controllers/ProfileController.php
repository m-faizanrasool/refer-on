<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    public function show()
    {
        $user_id = Auth::user()->id;

        $userTasks = Task::where(function ($q) use ($user_id) {
            $q->where('executor_id', $user_id)
                ->orWhere('submitter_id', $user_id);
        })
        ->whereNotIn('status', ['INVALID', 'BLACKLISTED'])->get();

        $fulfilledTasks = $userTasks->where('executor_id', $user_id);
        $tasksFulfilledByOthers = $userTasks->where('submitter_id', $user_id)->whereNotNull('executor_id');

        return Inertia::render('Profile/Show', [
            'fulfilledTasks' => $fulfilledTasks->count(),
            'fulfilledTasksEarnings' => $fulfilledTasks->pluck('brand.executor_credits')->sum(),
            'tasksFulfilledByOthers' => $tasksFulfilledByOthers->count(),
            'tasksFulfilledByOthersEarnings' => $tasksFulfilledByOthers->pluck('brand.submitter_credits')->sum(),
        ]);
    }

    public function detail($user_id = null)
    {
        $auth_user = Auth::user();

        if ($auth_user->is_admin && $user_id) {
            $user = User::findOrFail($user_id);
        } else {
            $user = $auth_user;
        }

        $tasks = Task::with(['submitter', 'executor'])
        ->where(function ($query) use ($user) {
            $query->where('submitter_id', $user->id)
                  ->orWhere('executor_id', $user->id);
        })->get();

        $formattedTasks = $tasks->map(function ($task) {
            $task->submitter_name = $task->submitter->username;
            $task->executor_name =  $task->executor ? $task->executor->username : "";
            $task->brand_name = $task->brand->name;
            $task->country_name = $task->brand->country_name;
            $task->submitter_credits = $task->executor ? $task->brand->submitter_credits : 0;
            $task->executor_credits =  $task->brand->executor_credits;
            $task->demerit_points = $task->status == "INVALID" || $task->status == 'BLACKLISTED' ? 1 : 0;
            $task->formatted_created_at = Carbon::parse($task->created_at)->format('d/m/Y');
            $task->can_dispute = ($task->fulfilled_at && Carbon::parse($task->fulfilled_at)->diffInDays(Carbon::now()) >= 15);
            return $task;
        });

        $fulfilledTasks = $formattedTasks->where('executor_id', $user->id);
        $tasksFulfilledByOthers = $formattedTasks->where('submitter_id', $user->id)->whereNotNull('executor_id');

        return Inertia::render('Profile/Detail', [
            'authId' => $user->id,
            'fulfilledTasks' => $fulfilledTasks->count(),
            'fulfilledTasksEarnings' => $fulfilledTasks->pluck('brand.executor_credits')->sum(),
            'tasksFulfilledByOthers' => $tasksFulfilledByOthers->count(),
            'tasksFulfilledByOthersEarnings' => $tasksFulfilledByOthers->pluck('brand.submitter_credits')->sum(),
            'tasks' => $formattedTasks,
        ]);
    }

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current-password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
