@extends('layouts.dashboard')

@section('title', 'Change Password - MARKETHING')

@section('page-title', 'Account Settings')
@section('page-subtitle', 'Update your password and keep your account secure.')

@section('user-name', 'Nova Marketing')
@section('user-role', 'Agency Account')

@section('dashboard-content')

<div class="settings-grid">

    <div class="settings-card">

        <div class="settings-header">
            <h2>Change Password</h2>
            <p>
                Enter your current password and choose a new secure password.
            </p>
        </div>

        <form>

            <div class="form-group">
                <label class="form-label">Current Password</label>

                <input
                    type="password"
                    class="form-input"
                    placeholder="Enter current password"
                >
            </div>

            <div class="form-group">
                <label class="form-label">New Password</label>

                <input
                    type="password"
                    class="form-input"
                    placeholder="Enter new password"
                >

                <p class="input-helper">
                    Minimum 8 characters, at least one letter and one number.
                </p>
            </div>

            <div class="form-group">
                <label class="form-label">Confirm New Password</label>

                <input
                    type="password"
                    class="form-input"
                    placeholder="Confirm new password"
                >
            </div>

            <div class="settings-actions">
                <x-button variant="btn-primary" type="button">
                    Save Password
                </x-button>

                <x-button variant="btn-secondary" type="button">
                    Cancel
                </x-button>
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