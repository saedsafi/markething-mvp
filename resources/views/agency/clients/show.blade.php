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
    $conversion = $brandInfo['conversion'] ?? [];

    function displayList($value) {
        if (is_array($value)) {
            return count($value) ? implode(', ', $value) : 'Not provided';
        }

        return $value ?: 'Not provided';
    }
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
                    <span class="hero-badge">Active Client</span>
                @else
                    <span class="hero-badge inactive-badge">Inactive Client</span>
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
                <form method="POST" action="{{ route('agency.clients.deactivate', $client) }}">
                    @csrf
                    @method('PATCH')

                    <button class="btn btn-danger" type="submit">
                        Deactivate
                    </button>
                </form>
            @else
                <form method="POST" action="{{ route('agency.clients.reactivate', $client) }}">
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
                        <h2 class="section-title">Business Context</h2>
                    </div>
                </div>

                <div class="profile-text-block">
                    {{ $client->business_context ?: 'No business context added yet.' }}
                </div>
            </div>

            <div class="table-card">
                <div class="section-header">
                    <div>
                        <h2 class="section-title">Business Fundamentals</h2>
                    </div>
                </div>

                <div class="info-grid">

                    <div class="info-item">
                        <span>Industry</span>
                        <strong>{{ $businessInfo['industry'] ?? $client->industry ?? 'Not provided' }}</strong>
                    </div>

                    <div class="info-item">
                        <span>Business Type</span>
                        <strong>{{ $businessInfo['business_type'] ?? 'Not provided' }}</strong>
                    </div>

                    <div class="info-item">
                        <span>Country</span>
                        <strong>{{ $businessInfo['country'] ?? 'Not provided' }}</strong>
                    </div>

                    <div class="info-item">
                        <span>City</span>
                        <strong>{{ displayList($businessInfo['city'] ?? []) }}</strong>
                    </div>

                    <div class="info-item">
                        <span>Price Tier</span>
                        <strong>{{ $businessInfo['price_tier'] ?? 'Not provided' }}/5</strong>
                    </div>

                    <div class="info-item">
                        <span>Business Age</span>
                        <strong>{{ $businessInfo['business_age'] ?? 'Not provided' }}</strong>
                    </div>

                </div>

                <div class="profile-section-divider"></div>

                <div class="persona-block">
                    <span>What sets this business apart</span>

                    <p>
                        {{ $businessInfo['differentiator'] ?? 'Not provided' }}
                    </p>
                </div>
            </div>

            <div class="table-card">
                <div class="section-header">
                    <div>
                        <h2 class="section-title">Market Positioning</h2>
                    </div>
                </div>

                <div class="info-grid">

                    <div class="info-item">
                        <span>Brand Positioning</span>
                        <strong>{{ displayList($businessInfo['brand_positioning'] ?? []) }}</strong>
                    </div>

                    <div class="info-item">
                        <span>Brand Avoids</span>
                        <strong>{{ displayList($businessInfo['brand_avoids'] ?? []) }}</strong>
                    </div>

                    @if (!empty($businessInfo['brand_avoids_other']))
                        <div class="info-item">
                            <span>Other Avoided Trait</span>
                            <strong>{{ $businessInfo['brand_avoids_other'] }}</strong>
                        </div>
                    @endif

                </div>
            </div>

            <div class="table-card">
                <div class="section-header">
                    <div>
                        <h2 class="section-title">Brand Voice & Rules</h2>
                    </div>
                </div>

                <div class="info-grid">

                    <div class="info-item">
                        <span>Arabic Variety / Dialect</span>
                        <strong>{{ $brandInfo['arabic_dialect'] ?? 'Not provided' }}</strong>
                    </div>

                    <div class="info-item">
                        <span>Emoji Usage</span>
                        <strong>{{ $brandInfo['emoji_usage'] ?? 'Not provided' }}</strong>
                    </div>

                    <div class="info-item">
                        <span>English Usage</span>
                        <strong>{{ $brandInfo['english_usage'] ?? 'Not provided' }}</strong>
                    </div>

                </div>

                <div class="profile-section-divider"></div>

                <div class="persona-block">
                    <span>Words & Phrases to Avoid</span>

                    <p>
                        {{ $brandInfo['words_to_avoid'] ?? 'None provided.' }}
                    </p>
                </div>

                <div class="persona-block">
                    <span>Caption Samples</span>

                    <p>
                        {{ $brandInfo['caption_samples'] ?? 'No caption samples provided.' }}
                    </p>
                </div>
            </div>

            <div class="table-card">
                <div class="section-header">
                    <div>
                        <h2 class="section-title">Marketing Mechanics</h2>
                    </div>
                </div>

                <div class="info-grid">

                    <div class="info-item">
                        <span>Conversion Actions</span>
                        <strong>{{ displayList($brandInfo['conversion_actions'] ?? []) }}</strong>
                    </div>

                    @foreach ($conversion as $key => $value)
                        @if ($value)
                            <div class="info-item">
                                <span>{{ ucwords(str_replace('_', ' ', $key)) }}</span>
                                <strong>{{ $value }}</strong>
                            </div>
                        @endif
                    @endforeach

                </div>
            </div>

            <div class="table-card">

                <div class="section-header">

                    <div>
                        <h2 class="section-title">Audience Personas</h2>

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
                                        <form method="POST" action="{{ route('agency.personas.deactivate', $persona) }}">
                                            @csrf
                                            @method('PATCH')

                                            <button class="mini-btn danger" type="submit">
                                                Deactivate
                                            </button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('agency.personas.reactivate', $persona) }}">
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
                                    <span>Gender</span>
                                    <p>{{ $answers['gender'] ?? 'Not provided' }}</p>
                                </div>

                                <div class="persona-block">
                                    <span>Audience Description</span>
                                    <p>{{ $answers['who'] ?? 'Not provided' }}</p>
                                </div>

                                <div class="persona-block">
                                    <span>Buyer / User Relationship</span>
                                    <p>{{ $answers['buyer_is_user'] ?? 'Not provided' }}</p>
                                </div>

                                @if (!empty($answers['decider']))
                                    <div class="persona-block">
                                        <span>Who Decides or Pays</span>
                                        <p>{{ $answers['decider'] }}</p>
                                    </div>
                                @endif

                                <div class="persona-block">
                                    <span>Priorities</span>
                                    <p>{{ displayList($answers['priorities'] ?? []) }}</p>
                                </div>

                                <div class="persona-block">
                                    <span>What Makes Them Hesitate</span>
                                    <p>{{ $answers['objection'] ?? 'Not provided' }}</p>
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

                <h2 class="section-title">Profile Status</h2>

                <div class="completion-list">

                    <div class="completion-item {{ $client->business_context ? 'done' : '' }}">
                        <span>{{ $client->business_context ? '✓' : '•' }}</span>
                        Business Context Added
                    </div>

                    <div class="completion-item done">
                        <span>✓</span>
                        Business Information Added
                    </div>

                    <div class="completion-item done">
                        <span>✓</span>
                        Brand Rules Added
                    </div>

                    <div class="completion-item {{ $client->personas->count() ? 'done' : '' }}">
                        <span>{{ $client->personas->count() ? '✓' : '•' }}</span>
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
    <form method="POST" action="{{ route('agency.personas.store', $client) }}">
        @csrf

        <div class="form-group">
            <label class="form-label">Persona Name</label>

            <input
                type="text"
                name="name"
                class="form-input"
                placeholder="Young Professionals"
                maxlength="50"
                required
            >
        </div>

        <div class="form-group">
            <label class="form-label">Gender</label>

            <select name="gender" class="form-select" required>
                <option value="">Select gender</option>
                <option value="Women — feminine address">Women — feminine address</option>
                <option value="Men — masculine address">Men — masculine address</option>
                <option value="Mixed (everyone) — inclusive forms">Mixed (everyone) — inclusive forms</option>
            </select>
        </div>

        <div class="form-group">
            <label class="form-label">Age Range</label>

            <select name="age_range" class="form-select" required>
                <option value="">Select age range</option>
                <option value="13–17">13–17</option>
                <option value="18–24">18–24</option>
                <option value="25–34">25–34</option>
                <option value="35–44">35–44</option>
                <option value="45–60">45–60</option>
                <option value="60+">60+</option>
                <option value="All ages">All ages</option>
            </select>
        </div>

        <div class="form-group">
            <label class="form-label">Who is this audience, in one line?</label>

            <input
                type="text"
                name="who"
                class="form-input"
                maxlength="80"
                placeholder="e.g., New mothers buying for their first baby"
                required
            >
        </div>

        <div class="form-group">
            <label class="form-label">Is the buyer the same as the user?</label>

            <select name="buyer_is_user" class="form-select" required>
                <option value="">Select answer</option>
                <option value="Yes — they buy it and use it themselves">Yes — they buy it and use it themselves</option>
                <option value="No — they buy it for someone else">No — they buy it for someone else</option>
                <option value="No — someone else buys it for them">No — someone else buys it for them</option>
            </select>
        </div>

        <div class="form-group">
            <label class="form-label">Who actually decides or pays?</label>

            <input
                type="text"
                name="decider"
                class="form-input"
                maxlength="60"
                placeholder="Required if buyer and user are not the same"
            >
        </div>

        <div class="form-group">
            <label class="form-label">What matters most to them?</label>

            <div class="checkbox-grid other-option">
                @foreach ([
                    'Price & offers',
                    'Quality',
                    'Speed & convenience',
                    'Trust & safety',
                    'Prestige & status',
                    'New & trendy',
                    'Personal service & care',
                ] as $priority)
                    <label class="checkbox-row">
                        <input
                            type="checkbox"
                            name="priorities[]"
                            value="{{ $priority }}"
                        >

                        <span>{{ $priority }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">What makes them hesitate?</label>

            <textarea
                name="objection"
                class="form-textarea"
                maxlength="150"
                placeholder="e.g., They worry the quality won’t match the price."
            ></textarea>
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