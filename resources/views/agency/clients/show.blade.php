@extends('layouts.dashboard')

@section('title', $client->name . ' - MARKETHING')

@section('page-title', $client->name)

@section(
    'page-subtitle',
    'Manage business context, personas, and campaign-ready marketing data.'
)

@section('user-name', auth()->user()->name ?? 'Nova Marketing')
@section('user-role', 'Agency Account')

@section('dashboard-content')

@php
    $businessInfo = $client->business_info ?? [];
    $brandInfo = $client->brand_info ?? [];
@endphp

<div class="client-profile-page">

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

    <div class="client-profile-hero">

        <div class="client-profile-main">

            <div class="client-logo large">
                {{ strtoupper(substr($client->name, 0, 1)) }}
            </div>

            <div>

                @if ($client->status === 'active')
                    <span class="hero-badge">
                        Active Client
                    </span>
                @else
                    <span class="hero-badge inactive-badge">
                        Inactive Client
                    </span>
                @endif

                <h2>{{ $client->name }}</h2>

                <p>
                    {{ $client->industry ?: 'Industry not specified' }}
                    · {{ $client->campaigns->count() }} campaigns
                    · {{ $client->personas->count() }} personas
                </p>

            </div>

        </div>

        <div class="hero-actions">

            <a
                href="{{ route('agency.clients.edit', $client) }}"
                class="btn btn-secondary"
            >
                Edit Profile
            </a>

            @if ($client->status === 'active')

                <form
                    method="POST"
                    action="{{ route('agency.clients.deactivate', $client) }}"
                >
                    @csrf
                    @method('PATCH')

                    <button class="btn btn-danger" type="submit">
                        Deactivate
                    </button>
                </form>

            @else

                <form
                    method="POST"
                    action="{{ route('agency.clients.reactivate', $client) }}"
                >
                    @csrf
                    @method('PATCH')

                    <button class="btn btn-primary" type="submit">
                        Reactivate
                    </button>
                </form>

            @endif

        </div>

    </div>

    <div class="client-profile-grid">

        <div class="profile-main-column">

            <div class="table-card">

                <div class="section-header">

                    <div>
                        <h2 class="section-title">
                            Business Context
                        </h2>
                    </div>

                </div>

                <div class="profile-text-block">
                    {{ $client->business_context ?: 'No business context added yet.' }}
                </div>

            </div>

            <div class="table-card">

                <div class="section-header">

                    <div>
                        <h2 class="section-title">
                            Business Information
                        </h2>
                    </div>

                </div>

                <div class="info-grid">

                    <div class="info-item">
                        <span>Business Offer</span>

                        <strong>
                            {{ $businessInfo['business_offer'] ?? 'Not provided' }}
                        </strong>
                    </div>

                </div>

            </div>

            <div class="table-card">

                <div class="section-header">

                    <div>
                        <h2 class="section-title">
                            Brand Information
                        </h2>
                    </div>

                </div>

                <div class="info-grid">

                    <div class="info-item">
                        <span>Brand Voice</span>

                        <strong>
                            {{ $brandInfo['brand_voice'] ?? 'Not provided' }}
                        </strong>
                    </div>

                    <div class="info-item">
                        <span>Brand Values</span>

                        <strong>
                            {{ $brandInfo['brand_values'] ?? 'Not provided' }}
                        </strong>
                    </div>

                </div>

                <div class="profile-section-divider"></div>

                <div class="profile-text-block">
                    {{ $brandInfo['brand_personality'] ?? 'No brand personality provided.' }}
                </div>

            </div>

            <div class="table-card">

                <div class="section-header">

                    <div>
                        <h2 class="section-title">
                            Audience Personas
                        </h2>

                        <p class="section-description">
                            Up to 5 active personas per client profile.
                        </p>
                    </div>

                    @if ($client->personas->where('status', 'active')->count() < 5)

                        <button
                            class="btn btn-primary"
                            type="button"
                            data-open-modal="addPersonaModal"
                        >
                            + Add Persona
                        </button>

                    @endif

                </div>

                <div class="persona-list">

                    @forelse ($client->personas as $persona)

                        @php
                            $answers = $persona->answers ?? [];
                        @endphp

                        <div class="persona-card">

                            <div class="persona-top">

                                <div>

                                    <h3>{{ $persona->name }}</h3>

                                    <p>
                                        {{ $persona->age_range ?: 'No age range' }}
                                    </p>

                                </div>

                                <div class="persona-actions">

                                    @if ($persona->status === 'active')

                                        <form
                                            method="POST"
                                            action="{{ route('agency.personas.deactivate', $persona) }}"
                                        >
                                            @csrf
                                            @method('PATCH')

                                            <button class="mini-btn danger" type="submit">
                                                Deactivate
                                            </button>
                                        </form>

                                    @else

                                        <form
                                            method="POST"
                                            action="{{ route('agency.personas.reactivate', $persona) }}"
                                        >
                                            @csrf
                                            @method('PATCH')

                                            <button class="mini-btn success" type="submit">
                                                Reactivate
                                            </button>
                                        </form>

                                    @endif

                                </div>

                            </div>

                            <div class="persona-content">

                                <div class="persona-block">

                                    <span>Description</span>

                                    <p>
                                        {{ $answers['description'] ?? 'No description provided.' }}
                                    </p>

                                </div>

                                <div class="persona-block">

                                    <span>Main Channel</span>

                                    <p>
                                        {{ $answers['channel'] ?? 'Not specified' }}
                                    </p>

                                </div>

                            </div>

                        </div>

                    @empty

                        <x-empty-state
                            title="No personas yet"
                            description="Create audience personas before generating campaigns."
                        />

                    @endforelse

                </div>

            </div>

        </div>

        <div class="profile-side-column">

            <div class="table-card sticky-card">

                <h2 class="section-title">
                    Profile Status
                </h2>

                <div class="completion-list">

                    <div class="completion-item done">
                        <span>✓</span>
                        Business Context Added
                    </div>

                    <div class="completion-item done">
                        <span>✓</span>
                        Brand Information Added
                    </div>

                    <div class="completion-item {{ $client->personas->count() ? 'done' : '' }}">
                        <span>
                            {{ $client->personas->count() ? '✓' : '•' }}
                        </span>

                        Persona Created
                    </div>

                </div>

                <div class="profile-side-divider"></div>

                <div class="quick-stats">

                    <div>
                        <span>Personas</span>
                        <strong>{{ $client->personas->count() }}</strong>
                    </div>

                    <div>
                        <span>Campaigns</span>
                        <strong>{{ $client->campaigns->count() }}</strong>
                    </div>

                    <div>
                        <span>Status</span>
                        <strong>{{ ucfirst($client->status) }}</strong>
                    </div>

                </div>

                <div class="save-actions">

                    <a
                        href="{{ route('agency.campaigns.create') }}"
                        class="btn btn-primary full-btn"
                    >
                        Generate Campaign
                    </a>

                    <a
                        href="{{ route('agency.clients.index') }}"
                        class="btn btn-secondary full-btn"
                    >
                        Back To Clients
                    </a>

                </div>

            </div>

        </div>

    </div>

</div>

<x-modal
    id="addPersonaModal"
    title="Add Persona"
    subtitle="Create another target audience persona for this client."
>

    <form
        method="POST"
        action="{{ route('agency.personas.store', $client) }}"
    >

        @csrf

        <div class="form-group">

            <label class="form-label">
                Persona Name
            </label>

            <input
                type="text"
                name="name"
                class="form-input"
                placeholder="Young Professionals"
                required
            >

        </div>

        <div class="form-group">

            <label class="form-label">
                Age Range
            </label>

            <input
                type="text"
                name="age_range"
                class="form-input"
                placeholder="25 - 35"
            >

        </div>

        <div class="form-group">

            <label class="form-label">
                Persona Description
            </label>

            <textarea
                name="description"
                class="form-textarea"
                placeholder="Describe interests, motivations, lifestyle, and behavior."
            ></textarea>

        </div>

        <div class="form-group">

            <label class="form-label">
                Main Platform
            </label>

            <input
                type="text"
                name="channel"
                class="form-input"
                placeholder="Instagram"
            >

        </div>

        <div class="modal-actions">

            <button class="btn btn-primary" type="submit">
                Add Persona
            </button>

            <button
                class="btn btn-secondary"
                type="button"
                data-close-modal
            >
                Cancel
            </button>

        </div>

    </form>

</x-modal>

@endsection