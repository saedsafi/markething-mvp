@extends('layouts.dashboard')

@section('title', 'Account Settings - MARKETHING')

@section('page-title', 'Account Settings')
@section('page-subtitle', 'Manage your agency account information and security.')

@section('user-name', auth()->user()->name)
@section('user-role', 'Agency Account')

@section('dashboard-content')

<div class="agency-settings-page fade-in">

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

    <div class="agency-settings-grid">

        <form method="POST" action="{{ route('agency.settings.update') }}">
            @csrf
            @method('PATCH')

            <div class="agency-settings-card">

                <div class="agency-settings-header">
                    <span class="hero-badge">Agency Profile</span>

                    <h2>Account Information</h2>

                    <p>
                        Update your agency display information. Account limits and access status are managed by the founder.
                    </p>
                </div>

                <div class="agency-settings-form-grid">

                    <div class="form-group">
                        <label class="form-label">Agency Name</label>

                        <input
                            type="text"
                            name="name"
                            class="form-input"
                            value="{{ old('name', auth()->user()->name) }}"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label class="form-label">Email Address</label>

                        <input
                            type="email"
                            name="email"
                            class="form-input"
                            value="{{ old('email', auth()->user()->email) }}"
                            required
                        >
                    </div>

                </div>

                <div class="agency-readonly-box">

                    <div>
                        <span>Client Limit</span>
                        <strong>{{ auth()->user()->client_limit }} clients</strong>
                    </div>

                    <div>
                        <span>AI Assist Today</span>
                    
                        <strong>
                            {{ $aiAssistUsedToday ?? 0 }} / {{ $aiAssistDailyLimit ?? auth()->user()->daily_ai_assist_limit }} assists
                        </strong>
                    </div>
                    
                    <div>
                        <span>Account Status</span>
                        <strong>{{ ucfirst(auth()->user()->status) }}</strong>
                    </div>

                </div>

                <div class="agency-settings-actions">
                    <button class="btn btn-primary" type="submit">
                        Save Changes
                    </button>
                </div>

            </div>
        </form>

        <div class="agency-settings-card security-card">

            <div class="agency-settings-header">
                <span class="hero-badge">Security</span>

                <h2>Password</h2>

                <p>
                    Change your password whenever needed to keep your account secure.
                </p>
            </div>

            <a
                href="{{ url('/change-password') }}"
                class="btn btn-edit"
            >
                Change Password
            </a>

        </div>

        <div class="agency-settings-card">

            <div class="agency-settings-header">
                <span class="hero-badge">AI Assist</span>

                <h2>AI Assist Preferences</h2>

                <p>
                    Reset your confirmation preference for replacing existing text with AI-generated drafts.
                </p>
            </div>

            <div class="agency-readonly-box">

                <div>
                    <span>Replace Confirmation</span>
                    <strong>Can be reset anytime</strong>
                </div>

            </div>

            <div class="agency-settings-actions">
                <button
                    class="btn btn-primary"
                    type="button"
                    id="resetAiAssistConfirmation"
                >
                    Reset “Don’t Ask Me Again”
                </button>
            </div>

        </div>

    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const resetButton = document.getElementById('resetAiAssistConfirmation');

    resetButton?.addEventListener('click', () => {
        localStorage.removeItem(
            'markething_ai_assist_skip_replace_confirmation'
        );

        alert('AI Assist replacement confirmation has been reset.');
    });
});
</script>

@endsection