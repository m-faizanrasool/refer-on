<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $validatedData = $this->validateData($request);

        $user = User::create($validatedData);

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }

    protected function validateData(Request $request): array
    {
        return $request->validate(
            $this->rules(),
            $this->messages()
        );
    }

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'phone' => ['required', 'unique:users','numeric'],
            'country_id' => ['required', 'numeric']
        ];
    }

    protected function messages(): array
    {
        return [
            'name.unique' => 'Username is already registered.',
            'phone.unique' => 'Mobile Number is already registered.',
            'email.unique' => 'Email is already registered.',
            'password.confirmed' => 'Passwords do not match.'
        ];
    }
}
