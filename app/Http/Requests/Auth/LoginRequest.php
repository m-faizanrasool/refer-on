<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'phone' => ['required','numeric'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $user = $this->findUser();

        $this->validateUserCredentials($user);

        $this->handleBlockedUser($user);

        Auth::login($user, $this->boolean('remember'));

        RateLimiter::clear($this->throttleKey());
    }

    private function findUser(): ?User
    {
        return User::where('phone', $this->input('phone'))->first();
    }

    private function validateUserCredentials(?User $user): void
    {
        if (!$user || !Hash::check($this->input('password'), $user->password)) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'phone' => trans('auth.failed'),
            ]);
        }
    }

    private function handleBlockedUser(User $user): void
    {
        $status = $user->status;

        if ($status === 'PERMANENTLY_BLOCKED') {
            throw ValidationException::withMessages([
                'phone' => trans('auth.permanently_blocked'),
            ]);
        }

        if ($status === 'BLOCKED') {
            $this->handleTemporaryBlockedUser($user);
        }
    }

    private function handleTemporaryBlockedUser(User $user): void
    {
        $blockedUntil = Carbon::parse($user->blocked_until);

        if ($blockedUntil->isFuture()) {
            throw ValidationException::withMessages([
                'phone' => trans('auth.blocked_until', ['date' => $blockedUntil->format('d-m-Y')]),
            ]);
        }

        $user->status = 'ACTIVE';
        $user->blocked_until = null;
        $user->save();
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'phone' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->input('phone')).'|'.$this->ip());
    }
}
