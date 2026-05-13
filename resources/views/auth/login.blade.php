@extends('layouts.app')

@section('title', 'Login - MARKETHING')

@section('content')

<div class="auth-wrapper">

    <div class="auth-card">

        <div class="logo">
            <div class="logo-box"></div>
            <h1>MARKETHING</h1>
        </div>

        <h2 class="auth-title">Welcome Back</h2>

        <p class="auth-subtitle">
            Sign in to manage campaigns, clients, and AI-powered marketing workflows.
        </p>

        @if ($errors->any())
            <div class="validation-box">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.store') }}">
            @csrf

            <div class="form-group">
                <label class="form-label">Email Address</label>

                <input
                    type="email"
                    name="email"
                    class="form-input"
                    placeholder="agency@example.com"
                    value="{{ old('email') }}"
                    required
                    autofocus
                >
            </div>

            <div class="form-group">
                <label class="form-label">Password</label>

                <input
                    type="password"
                    name="password"
                    class="form-input"
                    placeholder="Enter your password"
                    required
                >
            </div>

            <button class="primary-btn" type="submit">
                Sign In
            </button>

        </form>

        <p class="auth-note">
            Forgot your password? Contact the MARKETHING founder to issue a new temporary password.
        </p>

    </div>

</div>

@endsection