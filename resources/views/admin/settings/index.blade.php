@extends('layouts.dashboard')

@section('title', 'System Settings - MARKETHING')

@section('page-title', 'System Settings')
@section('page-subtitle', 'Configure tiers, limits, AI models, and campaign generation rules.')

@section('user-name', 'Founder Admin')
@section('user-role', 'Platform Owner')

@section('dashboard-content')

<div class="admin-settings-page">

    <div class="settings-config-grid">

        <x-config-card
            title="Tier Capacity Limits"
            description="Control user limits by plan tier."
            badge="Tiers"
        >
            <div class="config-form-grid">
                <div class="form-group">
                    <label class="form-label">Clients Per User</label>
                    <input class="form-input" type="number" value="10">
                </div>

                <div class="form-group">
                    <label class="form-label">Personas Per Client</label>
                    <input class="form-input" type="number" value="5">
                </div>

                <div class="form-group">
                    <label class="form-label">AI Assist Calls / Day</label>
                    <input class="form-input" type="number" value="50">
                </div>

                <div class="form-group">
                    <label class="form-label">Regenerations Per Post</label>
                    <input class="form-input" type="number" value="1">
                </div>
            </div>
        </x-config-card>

        <x-config-card
            title="Campaign Rules"
            description="Configure generation validation rules."
            badge="Campaigns"
        >
            <div class="config-form-grid">
                <div class="form-group">
                    <label class="form-label">Max Date Range</label>
                    <input class="form-input" type="number" value="90">
                </div>

                <div class="form-group">
                    <label class="form-label">Business Context Limit</label>
                    <input class="form-input" type="number" value="5000">
                </div>

                <div class="form-group">
                    <label class="form-label">Posts Minimum</label>
                    <input class="form-input" type="number" value="1">
                </div>

                <div class="form-group">
                    <label class="form-label">Default Timezone</label>
                    <input class="form-input" type="text" value="Asia/Hebron">
                </div>
            </div>
        </x-config-card>

        <x-config-card
            title="Master Prompt LLM Settings"
            description="Claude settings for campaign generation."
            badge="LLM"
        >
            <div class="config-form-grid">
                <div class="form-group">
                    <label class="form-label">Model</label>
                    <input class="form-input" type="text" value="claude-latest-stable">
                </div>

                <div class="form-group">
                    <label class="form-label">Temperature</label>
                    <input class="form-input" type="number" step="0.1" value="0.7">
                </div>

                <div class="form-group">
                    <label class="form-label">Max Tokens</label>
                    <input class="form-input" type="number" value="6000">
                </div>
            </div>
        </x-config-card>

        <x-config-card
            title="Assist Prompt LLM Settings"
            description="Claude settings for short AI Assist answers."
            badge="AI Assist"
        >
            <div class="config-form-grid">
                <div class="form-group">
                    <label class="form-label">Model</label>
                    <input class="form-input" type="text" value="claude-latest-stable">
                </div>

                <div class="form-group">
                    <label class="form-label">Temperature</label>
                    <input class="form-input" type="number" step="0.1" value="0.4">
                </div>

                <div class="form-group">
                    <label class="form-label">Max Tokens</label>
                    <input class="form-input" type="number" value="900">
                </div>
            </div>
        </x-config-card>

    </div>

    <div class="settings-save-bar">
        <p>Changes are frontend demo only until backend settings are connected.</p>

        <button class="btn btn-primary" type="button" data-save-settings>
            Save Settings
        </button>
    </div>

</div>

<x-toast id="appToast" title="Settings Saved" message="System settings were saved successfully." />

@endsection