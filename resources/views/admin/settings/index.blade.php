@extends('layouts.dashboard')

@section('title', 'System Settings - MARKETHING')

@section('page-title', 'System Settings')
@section('page-subtitle', 'Configure MVP campaign generation rules.')

@section('user-name', 'Founder Admin')
@section('user-role', 'Platform Owner')

@section('dashboard-content')

<div class="admin-settings-page">

    <div class="settings-config-grid single-config-grid">

        <x-config-card
            title="Campaign Rules"
            description="Configure the campaign generation limit required for MVP."
            badge="Campaigns"
        >
            <div class="config-form-grid">
                <div class="form-group">
                    <label class="form-label">Maximum Campaign Date Range</label>
                    <input class="form-input" type="number" value="90">
                    <p class="input-helper">
                        End date must be after start date. Maximum campaign date range is 90 days.
                    </p>
                </div>
            </div>
        </x-config-card>

    </div>

    <div class="settings-save-bar">
        <p>Only confirmed MVP configuration settings are shown here.</p>

        <button class="btn btn-primary" type="button" data-save-settings>
            Save Settings
        </button>
    </div>

</div>

<x-toast id="appToast" title="Settings Saved" message="System settings were saved successfully." />

@endsection