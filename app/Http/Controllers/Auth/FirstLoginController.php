<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FirstLoginController extends Controller
{
    public function show()
    {
        return view('auth.first-login');
    }

    public function update(Request $request)
    {
        $request->validate([
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/[A-Za-z]/',
                'regex:/[0-9]/',
            ],
        ], [
            'password.regex' => 'Password must contain at least one letter and one number.',
        ]);

        $user = Auth::user();

        $user->forceFill([
            'password' => $request->password,
            'must_change_password' => false,
            'status' => 'active',
            'password_changed_at' => now(),
            'last_login_at' => now(),
        ])->save();

        $request->session()->regenerate();

        if ($user->isFounder()) {
            return redirect('/admin/dashboard');
        }

        return redirect('/agency/dashboard');
    }
}