@extends('layouts.dashboard')

@section('title', 'Agency Dashboard - MARKETHING')

@section('page-title', 'Agency Dashboard')

@section(
    'page-subtitle',
    'Monitor clients, personas, campaigns, and AI-powered marketing workflows.'
)

@section('user-name', auth()->user()->name ?? 'Agency User')
@section('user-role', 'Agency Account')

@section('dashboard-content')

<div class="agency-dashboard">

    @if (session('success'))
        <div class="validation-box success-box">
            {{ session('success') }}
        </div>
    @endif

    <div class="dashboard-hero">

        <div>
            <span class="hero-badge">
                {{ $activeClients }} active clients
            </span>

            <h2>
                Welcome back, {{ auth()->user()->name }}
            </h2>

            <p>
                Manage client profiles, generate campaigns,
                and organize AI-powered marketing operations.
            </p>
        </div>

        <div class="hero-actions">

            <a
                href="{{ route('agency.clients.create') }}"
                class="btn btn-primary"
            >
                + Create Client Profile
            </a>

            <a
                href="{{ route('agency.campaigns.create') }}"
                class="btn btn-secondary"
            >
                + Create Campaign
            </a>

        </div>

    </div>

    <div class="stats-grid">

        <x-stats-card
            label="Active Clients"
            value="{{ $activeClients }}"
            hint="Available business profiles"
        />

        <x-stats-card
            label="Inactive Clients"
            value="{{ $inactiveClients }}"
            hint="Deactivated profiles"
        />

        <x-stats-card
            label="Personas"
            value="{{ $totalPersonas }}"
            hint="Audience personas"
        />

        <x-stats-card
            label="Generated Campaigns"
            value="{{ $generatedCampaigns }}"
            hint="Successfully generated"
        />

    </div>

    <div class="dashboard-grid">

        <div class="dashboard-main-column">

            <x-data-table title="Recent Client Profiles">

                <x-slot name="action">
                    <a
                        href="{{ route('agency.clients.index') }}"
                        class="mini-btn"
                    >
                        View All
                    </a>
                </x-slot>

                <table class="dashboard-table">

                    <thead>
                        <tr>
                            <th>Client</th>
                            <th>Personas</th>
                            <th>Campaigns</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse ($clients as $client)

                            <tr>

                                <td>
                                    <strong>{{ $client->name }}</strong>

                                    <p class="table-muted">
                                        {{ $client->industry ?: 'No industry set' }}
                                    </p>
                                </td>

                                <td>
                                    {{ $client->personas_count }}
                                </td>

                                <td>
                                    {{ $client->campaigns_count }}
                                </td>

                                <td>

                                    @if ($client->status === 'active')

                                        <span class="status active-status">
                                            Active
                                        </span>

                                    @else

                                        <span class="status inactive-status">
                                            Inactive
                                        </span>

                                    @endif

                                </td>

                                <td>

                                    <a
                                        href="{{ route('agency.clients.show', $client) }}"
                                        class="mini-btn"
                                    >
                                        View
                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="5">
                                    No client profiles yet.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </x-data-table>

            <x-data-table title="Recent Campaigns">

                <table class="dashboard-table">

                    <thead>
                        <tr>
                            <th>Campaign</th>
                            <th>Status</th>
                            <th>Created</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse ($campaigns as $campaign)

                            <tr>

                                <td>
                                    <strong>{{ $campaign->name }}</strong>

                                    <p class="table-muted">
                                        {{ $campaign->objective }}
                                    </p>
                                </td>

                                <td>

                                    @if ($campaign->status === 'generated')

                                        <span class="status active-status">
                                            Generated
                                        </span>

                                    @elseif ($campaign->status === 'failed')

                                        <span class="status suspended-status">
                                            Failed
                                        </span>

                                    @else

                                        <span class="status inactive-status">
                                            Generating
                                        </span>

                                    @endif

                                </td>

                                <td>
                                    {{ $campaign->created_at->format('M d, Y') }}
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="3">
                                    No campaigns generated yet.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </x-data-table>

        </div>

        <div class="agency-side-stack">

            <div class="table-card">

                <h2 class="section-title">
                    Workflow Progress
                </h2>

                <div class="completion-list">

                    <div class="completion-item done">
                        <span>✓</span>
                        Client Profiles
                    </div>

                    <div class="completion-item done">
                        <span>✓</span>
                        Audience Personas
                    </div>

                    <div class="completion-item active">
                        <span>•</span>
                        Campaign Generation
                    </div>

                </div>

            </div>

            <div class="table-card">

                <h2 class="section-title">
                    Account Limits
                </h2>

                <div class="limit-list">

                    <div class="limit-row">

                        <div>
                            <strong>Client Profiles</strong>

                            <p>
                                {{ $activeClients }}
                                active of
                                {{ auth()->user()->client_limit }}
                                allowed.
                            </p>
                        </div>

                        @php
                            $clientPercentage =
                                auth()->user()->client_limit > 0
                                    ? min(
                                        100,
                                        ($activeClients / auth()->user()->client_limit) * 100
                                    )
                                    : 0;
                        @endphp

                        <div class="limit-bar">
                            <span style="width:{{ $clientPercentage }}%;"></span>
                        </div>

                    </div>

                    <div class="limit-row">

                        <div>
                            <strong>Daily AI Assist</strong>

                            <p>
                                {{ auth()->user()->daily_ai_assist_limit }}
                                daily assist actions available.
                            </p>
                        </div>

                        <div class="limit-bar">
                            <span style="width:35%;"></span>
                        </div>

                    </div>

                </div>

            </div>

            <div class="table-card">

                <h2 class="section-title">
                    Campaign Health
                </h2>

                <div class="quick-stats">

                    <div>
                        <span>Generated</span>
                        <strong>{{ $generatedCampaigns }}</strong>
                    </div>

                    <div>
                        <span>Failed</span>
                        <strong>{{ $failedCampaigns }}</strong>
                    </div>

                    <div>
                        <span>Total</span>
                        <strong>{{ $campaigns->count() }}</strong>
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection