@extends('layouts.dashboard')

@section('title', 'Clients - MARKETHING')

@section('page-title', 'Client Profiles')
@section('page-subtitle', 'Create and manage the businesses you generate campaigns for.')

@section('user-name', auth()->user()->name ?? 'Nova Marketing')
@section('user-role', 'Agency Account')

@section('dashboard-content')

<div class="clients-page">

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

    <div class="clients-header-card">

        <div>
            <span class="hero-badge">
                {{ $activeClients ?? 0 }} active client profiles
            </span>

            <h2>Your Client Workspace</h2>

            <p>
                Each client profile stores business context, brand information, personas,
                and campaign-ready marketing details.
            </p>
        </div>

        <div class="hero-actions">
            @if (($activeClients ?? 0) >= auth()->user()->client_limit)
            <button
                class="btn btn-primary"
                type="button"
                onclick="alert('You’ve reached your client profiles limit.')"
            >
                + Create Client Profile
            </button>
        @else
            <a href="{{ route('agency.clients.create') }}" class="btn btn-primary">
                + Create Client Profile
            </a>
        @endif
        </div>

    </div>

    <div class="stats-grid">

        <x-stats-card
            label="Active Clients"
            value="{{ $activeClients ?? 0 }}"
            hint="Available for campaigns"
        />

        <x-stats-card
            label="Inactive Clients"
            value="{{ $inactiveClients ?? 0 }}"
            hint="Deactivated profiles"
        />

        <x-stats-card
            label="Total Personas"
            value="{{ $totalPersonas ?? 0 }}"
            hint="Across all clients"
        />

    </div>

    <div class="client-grid">

        @forelse ($clients as $client)

            <div class="client-card {{ $client->status === 'inactive' ? 'deactivated' : '' }}">

                <div class="client-card-top">
                    <div class="client-logo">
                        {{ strtoupper(substr($client->name, 0, 1)) }}
                    </div>

                    <div>
                        <h3>{{ $client->name }}</h3>

                        <p>
                            {{ $client->industry ?: 'No industry set' }}
                            @if ($client->status === 'inactive')
                                · Inactive profile
                            @endif
                        </p>
                    </div>
                </div>

                <div class="client-info-list">
                    <div>
                        <span>Personas</span>
                        <strong>{{ $client->personas_count }}</strong>
                    </div>

                    <div>
                        <span>Campaigns</span>
                        <strong>{{ $client->campaigns_count }}</strong>
                    </div>

                    <div>
                        <span>Status</span>
                        <strong>{{ ucfirst($client->status) }}</strong>
                    </div>
                </div>

                <div class="client-actions">

                    <a href="{{ route('agency.clients.show', $client) }}" class="mini-btn">
                        View Profile
                    </a>

                    @if ($client->status === 'active')
                        <a href="{{ route('agency.clients.edit', $client) }}" class="mini-btn">
                            Edit
                        </a>

                        <form method="POST" action="{{ route('agency.clients.deactivate', $client) }}">
                            @csrf
                            @method('PATCH')

                            <button class="mini-btn danger" type="submit">
                                Deactivate
                            </button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('agency.clients.reactivate', $client) }}">
                            @csrf
                            @method('PATCH')

                            <button class="mini-btn success" type="submit">
                                Reactivate
                            </button>
                        </form>
                    @endif

                </div>

            </div>

        @empty

            <x-empty-state
                title="No client profiles yet"
                description="Create your first client profile before generating campaigns."
            >
                <x-slot name="action">
                    <a href="{{ route('agency.clients.create') }}" class="btn btn-primary">
                        + Create Client Profile
                    </a>
                </x-slot>
            </x-empty-state>

        @endforelse

    </div>

    <div class="capacity-card">
        <div>
            <h3>Client Capacity</h3>
            <p>
                You have {{ $activeClients ?? 0 }} active clients.
                Your maximum is {{ auth()->user()->client_limit }}.
            </p>
        </div>

        <div class="capacity-bar">
            @php
                $percentage = auth()->user()->client_limit > 0
                    ? min(100, (($activeClients ?? 0) / auth()->user()->client_limit) * 100)
                    : 0;
            @endphp

            <span style="width:{{ $percentage }}%;"></span>
        </div>
    </div>

</div>

@endsection