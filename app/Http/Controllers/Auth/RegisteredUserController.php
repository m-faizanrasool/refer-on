<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    public function store(Request $request): RedirectResponse
    {
        $validatedData = $this->getValidatedData($request);

        $user = User::create($validatedData);

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME)->with('message', 'Register Success, Welcome To Community');
    }

    protected function getValidatedData(Request $request): array
    {
        return $request->validate(
            $this->rules(),
            $this->messages()
        );
    }

    public function validateData(Request $request)
    {
        $request->validate(
            $this->rules(),
            $this->messages()
        );
    }

    protected function rules(): array
    {
        return [
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->whereNull('deleted_at'), 'regex:/^\S*$/'],
            'email' => 'required|string|email|max:255|' . Rule::unique('users')->whereNull('deleted_at'),
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'phone' => ['required', Rule::unique('users')->whereNull('deleted_at'),'numeric'],
            'country_id' => ['required', 'numeric']
        ];
    }

    protected function messages(): array
    {
        return [
            'username.unique' => 'Username is already registered.',
            'username.regex' => 'The username must not contain any spaces.',
            'phone.unique' => 'Mobile Number is already registered.',
            'email.unique' => 'Email is already registered.',
            'password.confirmed' => 'Passwords do not match.'
        ];
    }
}
