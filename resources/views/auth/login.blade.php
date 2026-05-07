@extends('layouts.app')

@section('title', 'Login - MARKETHING')

@section('content')
<div class="auth-wrapper">

    <div class="auth-card">

        <div class="logo">
            <div class="logo-icon"></div>
            <h1>MARKETHING</h1>
        </div>

        <h2 class="auth-title">Welcome Back</h2>

        <p class="auth-subtitle">
            Login to your marketing agency workspace.
        </p>

        <form>

            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input type="email" class="form-input" placeholder="agency@example.com">
            </div>

            <div class="form-group">
                <label class="form-label">Password</label>
                <input type="password" class="form-input" placeholder="Enter password">
            </div>

            <button class="primary-btn">
                Login
            </button>

        </form>

    </div>

</div>
@endsection