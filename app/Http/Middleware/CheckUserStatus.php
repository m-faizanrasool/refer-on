<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserStatus
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
        $user = Auth::user();

        if ($user->status === 'BLOCKED') {
            $blockedUntil = Carbon::parse($user->blocked_until);

            Auth::logout();
            return redirect()->route('login')->with('error', 'Your account has been blocked until ' . $blockedUntil->format('d-m-Y') . '.');
        }

        if ($user->status === 'PERMANENTLY_BLOCKED') {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Your account has been permanently blocked.');
        }

        return $next($request);
    }
}
