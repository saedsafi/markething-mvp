@extends('layouts.app')

@section('title', 'Account Suspended - MARKETHING')

@section('content')

<div class="auth-wrapper">

    <div class="auth-card suspended-card">

        <div class="logo auth-logo">
            <div class="logo-box"></div>
        
            <img
                src="{{ asset('images/logo.svg') }}"
                alt="MARKETHING"
            >
        </div>

        <div class="suspended-icon">!</div>

        <h2 class="auth-title">Account Suspended</h2>

        <p class="auth-subtitle">
            Your account is currently suspended and cannot access MARKETHING.
        </p>

        <div class="suspended-message">
            Please contact the platform founder or support contact to reactivate your account.
        </div>

        <button class="primary-btn" type="button" data-back-login>
            Back To Login
        </button>

    </div>

</div>

@endsection