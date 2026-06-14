@extends('layouts.app')

@section('title', 'First Login - MARKETHING')

@section('content')

<div class="auth-wrapper">

    <div class="auth-card first-login-card">
        <div class="first-login-glow"></div>

        <div class="logo auth-logo">
            <div class="logo-box"></div>

            <img
                src="{{ asset('images/logo.svg') }}"
                alt="MARKETHING"
            >
        </div>

        <div class="auth-badge first-login-badge">
            Security Setup
        </div>
        
        <h2 class="auth-title">Secure Your Account</h2>

        <p class="auth-subtitle">
            You are using a temporary password. Create a new password before entering your dashboard.
        </p>

        @if ($errors->any())
            <div class="validation-box">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('first-login.update') }}">
            @csrf

            <div class="form-group">
                <label class="form-label">New Password</label>

                <input
                    type="password"
                    name="password"
                    class="form-input"
                    placeholder="Create new password"
                    required
                >

                <p class="input-helper">
                    Minimum 8 characters, at least one letter and one number.
                </p>
            </div>

            <div class="form-group">
                <label class="form-label">Confirm New Password</label>

                <input
                    type="password"
                    name="password_confirmation"
                    class="form-input"
                    placeholder="Confirm new password"
                    required
                >
            </div>

            <button class="primary-btn" type="submit">
                Continue To Dashboard
            </button>

        </form>

        <div class="security-tip">
            <div class="security-tip-icon">🛡️</div>
        
            <div>
                <strong>Security Check</strong>
        
                <p>
                    This is a one-time step required for all new agency accounts.
                </p>
            </div>
        </div>

    </div>

</div>

@endsection