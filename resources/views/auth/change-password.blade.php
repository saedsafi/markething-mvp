@extends('layouts.dashboard')

@section('title', 'Change Password - MARKETHING')

@section('page-title', 'Account Settings')
@section('page-subtitle', 'Update your password and keep your account secure.')

@section('user-name', auth()->user()->name ?? 'Nova Marketing')
@section('user-role', auth()->user()?->isFounder() ? 'Platform Owner' : 'Agency Account')

@section('dashboard-content')

<div class="settings-grid">

    <div class="settings-card">

        <div class="settings-header">
            <h2>Change Password</h2>
            <p>
                Enter your current password and choose a new secure password.
            </p>
        </div>

        @if (session('success'))
            <div class="validation-box success-box">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="validation-box">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            <div class="form-group">
                <label class="form-label">Current Password</label>

                <input
                    type="password"
                    name="current_password"
                    class="form-input"
                    placeholder="Enter current password"
                    required
                >
            </div>

            <div class="form-group">
                <label class="form-label">New Password</label>

                <input
                    type="password"
                    name="password"
                    class="form-input"
                    placeholder="Enter new password"
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

            <div class="settings-actions">
                <button class="btn btn-primary" type="submit">
                    Save Password
                </button>

                <a href="{{ auth()->user()?->isFounder() ? url('/admin/dashboard') : url('/agency/dashboard') }}" class="btn btn-secondary">
                    Cancel
                </a>
            </div>

        </form>

    </div>

    <div class="security-card">

        <div class="security-icon">🔐</div>

        <h3>Password Security</h3>

        <p>
            After changing your password, all other active sessions will be signed out.
            Your current session will stay active.
        </p>

    </div>

</div>

@endsection