<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $user =
            User::orderByDesc('id')->when($request->search, function ($query, $search) {
                $query->where('username', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            })
            ->where('id', '!=', Auth::id()) // Exclude current logged-in user
            ->paginate(10);

        return Inertia::render(
            'User/Index',
            [
                'users' => $user
            ]
        );
    }

    public function block($user_id)
    {
        $user = User::findOrFail($user_id);

        $user->status = "PERMANENTLY_BLOCKED";
        $user->save();

        return redirect()->route('user.index')->with('message', 'Successfully blocked user');
    }
}
