@extends('layouts.dashboard')

@section('title', 'System Settings - MARKETHING')

@section('page-title', 'System Settings')
@section('page-subtitle', 'Configure MVP generation and usage limits.')

@section('user-name', 'Founder Admin')
@section('user-role', 'Platform Owner')

@section('dashboard-content')

<div class="admin-settings-page fade-in">

    <form method="POST" action="{{ route('admin.settings.update') }}">
        @csrf
        @method('PATCH')

        <div class="settings-config-grid">

            <div class="config-card">

                <div class="config-card-header">
                    <h2>System Limits</h2>
                    <p>Configure MVP limits used across campaigns, personas, regeneration, and AI Assist.</p>
                </div>

                <div class="config-card-body">

                    @php
                        $fields = [
                            'max_campaign_days' => [
                                'label' => 'Maximum Campaign Date Range',
                                'max' => 30,
                                'default' => 30,
                                'helper' => 'Campaigns cannot exceed 30 days.',
                            ],
                            'max_personas_per_client' => [
                                'label' => 'Maximum Personas Per Client',
                                'max' => 5,
                                'default' => 5,
                                'helper' => 'Each client profile can have up to 5 personas.',
                            ],
                            'max_regenerations_per_post' => [
                                'label' => 'Maximum Regenerations Per Post',
                                'max' => 1,
                                'default' => 1,
                                'helper' => 'Each generated post can be regenerated once.',
                            ],
                            'ai_assist_daily_limit' => [
                                'label' => 'AI Assist Calls Per User Per Day',
                                'max' => 50,
                                'default' => 50,
                                'helper' => 'Default maximum is 50 AI Assist calls per user per day.',
                            ],
                            'business_context_character_limit' => [
                                'label' => 'Business Context Character Limit',
                                'max' => 5000,
                                'default' => 5000,
                                'helper' => 'Business Context input cannot exceed 5,000 characters.',
                            ],
                        ];
                    @endphp

                    @foreach ($fields as $name => $field)

                        <div class="form-group">

                            <label class="form-label">
                                {{ $field['label'] }}
                            </label>

                            <input
                                class="form-input"
                                type="number"
                                name="{{ $name }}"
                                min="1"
                                max="{{ $field['max'] }}"
                                value="{{ old($name, $settings[$name] ?? $field['default']) }}"
                            >

                            <p class="input-helper">
                                {{ $field['helper'] }}
                            </p>

                            @error($name)
                                <p class="form-error">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>

                    @endforeach

                </div>

            </div>

        </div>

        <div class="settings-save-bar">
            <button class="btn btn-primary" type="submit">
                Save Settings
            </button>
        </div>

    </form>

</div>

@endsection