<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'ime' => [
                'required',
                'string',
                'regex:/^[A-Z]{1}[a-zA-Z]{3,15}$/'
            ],
            'prezime' => [
                'required',
                'string',
                'regex:/^[A-Z]{1}[a-zA-Z]{3,15}$/'
            ],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                'unique:' . User::class
            ],
            'adresa' => [
                'required',
                'string',
                'regex:/^[a-zA-Z]{1,15}(\s[a-zA-Z]{1,15})?(\s[a-zA-Z]{1,12})?(\s[a-zA-Z0-9]{1,20})?\s[a-zA-Z0-9]{1,3}\,\s[0-9]{5}\s[a-zA-z]{3,13}$/'
            ],
            'tel' => [
                'required',
                'string',
                'regex:/^\+3816([0-9]){6,9}$/'
            ],
            'password' => [
                'required',
                'confirmed',
                Rules\Password::min(8)
                    ->max(16)
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised()
            ],
        ]);

        $user = User::create([
            'ime' => $request->ime,
            'prezime' => $request->prezime,
            'email' => $request->email,
            'adresa' => $request->adresa,
            'tel' => $request->tel,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('verification.notice');
    }
}
