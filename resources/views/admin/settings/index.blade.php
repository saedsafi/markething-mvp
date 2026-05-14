@extends('layouts.dashboard')

@section('title', 'System Settings - MARKETHING')

@section('page-title', 'System Settings')
@section('page-subtitle', 'Configure MVP campaign generation rules.')

@section('user-name', 'Founder Admin')
@section('user-role', 'Platform Owner')

@section('dashboard-content')

<div class="admin-settings-page fade-in">

    <form
        method="POST"
        action="{{ route('admin.settings.update') }}"
    >

        @csrf
        @method('PATCH')

        <div class="settings-config-grid">

            <div class="config-card">

                <div class="config-card-header">

                    <h2>
                        Campaign Rules
                    </h2>

                    <p>
                        Configure MVP campaign generation limits.
                    </p>

                </div>

                <div class="config-card-body">

                    <div class="form-group">

                        <label class="form-label">
                            Maximum Campaign Date Range
                        </label>

                        <input
                            class="form-input"
                            type="number"
                            name="max_campaign_days"
                            min="1"
                            max="90"
                            value="{{ old('max_campaign_days', $settings['max_campaign_days'] ?? 90) }}"
                        >

                        <p class="input-helper">
                            Campaigns cannot exceed 90 days.
                        </p>

                        @error('max_campaign_days')

                            <p class="form-error">
                                {{ $message }}
                            </p>

                        @enderror

                    </div>

                </div>

            </div>

        </div>

        <div class="settings-save-bar">

            <button
                class="btn btn-primary"
                type="submit"
                >
                Save Settings
            </button>

        </div>

    </form>

</div>

@endsection