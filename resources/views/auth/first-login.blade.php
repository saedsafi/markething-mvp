@extends('layouts.app')

@section('title', 'Setup Account - MARKETHING')

@section('content')
<div class="auth-wrapper">

    <div class="auth-card">

        <div class="logo">
            <div class="logo-icon"></div>
            <h1>MARKETHING</h1>
        </div>

        <h2 class="auth-title">Secure Your Account</h2>

        <p class="auth-subtitle">
            This is your first login. Please create a secure password.
        </p>

        <form>

            <div class="form-group">
                <label class="form-label">Temporary Password</label>
                <input type="password" class="form-input">
            </div>

            <div class="form-group">
                <label class="form-label">New Password</label>
                <input type="password" class="form-input">
            </div>

            <div class="form-group">
                <label class="form-label">Confirm Password</label>
                <input type="password" class="form-input">
            </div>

            <button class="primary-btn">
                Continue To Dashboard
            </button>

        </form>

    </div>

</div>
@endsection