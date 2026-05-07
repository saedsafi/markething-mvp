@extends('layouts.app')

@section('title', 'First Login - MARKETHING')

@section('content')

<div class="auth-wrapper">

    <div class="auth-card">

        <div class="logo">
            <div class="logo-box"></div>
            <h1>MARKETHING</h1>
        </div>

        <div class="auth-badge">First Login Required</div>

        <h2 class="auth-title">Secure Your Account</h2>

        <p class="auth-subtitle">
            You are using a temporary password. Create a new password before entering your dashboard.
        </p>

        <form>

            <div class="form-group">
                <label class="form-label">Temporary Password</label>

                <input
                    type="password"
                    class="form-input"
                    placeholder="Enter temporary password"
                >
            </div>

            <div class="form-group">
                <label class="form-label">New Password</label>

                <input
                    type="password"
                    class="form-input"
                    placeholder="Create new password"
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

            <button class="primary-btn" type="button" data-first-login-submit>
                Continue To Dashboard
            </button>

        </form>

    </div>

</div>

@endsection