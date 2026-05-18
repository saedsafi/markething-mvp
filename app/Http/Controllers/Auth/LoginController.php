<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function show()
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {
        $this->ensureIsNotRateLimited($request);

        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $remember = true;

        if (! Auth::attempt($credentials, $remember)) {

            $this->incrementLoginAttempts($request);

            $attempts = RateLimiter::attempts(
                $this->throttleKey($request)
            );

            $remainingAttempts = 10 - $attempts;

            $message = 'Invalid email or password.';

            if ($attempts >= 5 && $remainingAttempts > 0) {

                $message .=
                    ' Warning: ' .
                    $remainingAttempts .
                    ' login attempts remaining before temporary lock.';
            }

            return back()
                ->withErrors([
                    'email' => $message,
                ])
                ->onlyInput('email');
        }

        RateLimiter::clear(
            $this->throttleKey($request)
        );

        $request->session()->regenerate();

        $user = Auth::user();

        if ($user->isSuspended()) {

            Auth::logout();

            $request->session()->invalidate();

            $request->session()->regenerateToken();

            return redirect('/suspended');
        }

        if (
            $user->must_change_password ||
            $user->isInactive()
        ) {
            return redirect('/first-login');
        }

        $user->forceFill([
            'last_login_at' => now(),
        ])->save();

        if ($user->isFounder()) {
            return redirect('/admin/dashboard');
        }

        return redirect('/agency/dashboard');
    }

    protected function throttleKey(
        Request $request
    ): string {

        return Str::lower(
            $request->email
        ) . '|' . $request->ip();
    }

    protected function ensureIsNotRateLimited(
        Request $request
    ): void {

        $key = $this->throttleKey($request);

        if (! RateLimiter::tooManyAttempts($key, 10)) {
            return;
        }

        throw ValidationException::withMessages([
            'email' => [
                'Too many failed login attempts. Please try again in 24 hours.',
            ],
        ]);
    }

    protected function incrementLoginAttempts(
        Request $request
    ): void {

        $key = $this->throttleKey($request);

        RateLimiter::hit(
            $key,
            60 * 60 * 24
        );
    }
}