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

    $personaLimit = $personaLimit ?? 5;
    $aiDisabled = blank(trim((string) $client->business_context));

    $personaPriorities = [
        'Price & offers',
        'Quality',
        'Speed & convenience',
        'Trust & safety',
        'Prestige & status',
        'New & trendy',
        'Personal service & care',
    ];

    $displayList = function ($value) {
        if (is_array($value)) {
            return count($value) ? implode(', ', $value) : 'Not provided';
        }

        return $value ?: 'Not provided';
    };
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
                class="btn btn-edit"
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

            <form
                method="POST"
                action="{{ route('agency.clients.destroy', $client) }}"
                data-confirm="Delete this client profile permanently? Existing campaign snapshots will remain protected."
            >
                @csrf
                @method('DELETE')

                <button class="btn btn-danger" type="submit">
                    Delete Client
                </button>
            </form>

        </div>

    </div>

    <div class="client-profile-grid">

        <div class="profile-main-column">

            <div class="table-card">
                <div class="section-header">
                    <h2 class="section-title">Business Context</h2>
                </div>

                <div class="profile-text-block">
                    {{ $client->business_context ?: 'No business context added yet.' }}
                </div>
            </div>

            <div class="table-card">
                <div class="section-header">
                    <h2 class="section-title">Business Fundamentals</h2>
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
                        <strong>{{ $displayList($businessInfo['city'] ?? []) }}</strong>
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
                    <p>{{ $businessInfo['differentiator'] ?? 'Not provided' }}</p>
                </div>
            </div>

            <div class="table-card">
                <div class="section-header">
                    <h2 class="section-title">Market Positioning</h2>
                </div>

                <div class="info-grid">

                    <div class="info-item">
                        <span>Brand Positioning</span>
                        <strong>{{ $displayList($businessInfo['brand_positioning'] ?? []) }}</strong>
                    </div>

                    <div class="info-item">
                        <span>Brand Avoids</span>
                        <strong>{{ $displayList($businessInfo['brand_avoids'] ?? []) }}</strong>
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
                    <h2 class="section-title">Brand Voice & Rules</h2>
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
                    <p>{{ $brandInfo['words_to_avoid'] ?? 'None provided.' }}</p>
                </div>

                <div class="persona-block">
                    <span>Caption Samples</span>
                    <p>{{ $brandInfo['caption_samples'] ?? 'No caption samples provided.' }}</p>
                </div>
            </div>

            <div class="table-card">
                <div class="section-header">
                    <h2 class="section-title">Marketing Mechanics</h2>
                </div>

                <div class="info-grid">

                    <div class="info-item">
                        <span>Conversion Actions</span>
                        <strong>{{ $displayList($brandInfo['conversion_actions'] ?? []) }}</strong>
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
                            Up to {{ $personaLimit }} active personas per client profile.
                        </p>
                    </div>

                    @if ($client->personas->where('status', 'active')->count() < $personaLimit)
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

                                    <button
                                        class="mini-btn"
                                        type="button"
                                        data-open-modal="editPersonaModal{{ $persona->id }}"
                                    >
                                        Edit
                                    </button>

                                    <form
                                        method="POST"
                                        action="{{ route('agency.personas.destroy', $persona) }}"
                                        data-confirm="Delete this persona permanently?"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button class="mini-btn danger" type="submit">
                                            Delete
                                        </button>
                                    </form>

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
                                    <p>{{ $displayList($answers['priorities'] ?? []) }}</p>
                                </div>

                                <div class="persona-block">
                                    <span>What Makes Them Hesitate</span>
                                    <p>{{ $answers['objection'] ?? 'Not provided' }}</p>
                                </div>

                            </div>

                        </div>

                        <x-modal
                            id="editPersonaModal{{ $persona->id }}"
                            title="Edit Persona"
                            subtitle="Update this audience persona."
                        >
                            <form
                                method="POST"
                                action="{{ route('agency.personas.update', $persona) }}"
                                data-persona-form
                            >
                                @csrf
                                @method('PATCH')

                                <div class="form-group">
                                    <label class="form-label">Persona Name</label>

                                    <input
                                        type="text"
                                        name="name"
                                        class="form-input"
                                        value="{{ $persona->name }}"
                                        maxlength="50"
                                        required
                                    >
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Gender</label>

                                    <select name="gender" class="form-select" required>
                                        @foreach ([
                                            'Women — feminine address',
                                            'Men — masculine address',
                                            'Mixed (everyone) — inclusive forms',
                                        ] as $option)
                                            <option
                                                value="{{ $option }}"
                                                @selected(($answers['gender'] ?? '') === $option)
                                            >
                                                {{ $option }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Age Range</label>

                                    <select name="age_range" class="form-select" required>
                                        @foreach (['13–17', '18–24', '25–34', '35–44', '45–60', '60+', 'All ages'] as $option)
                                            <option
                                                value="{{ $option }}"
                                                @selected($persona->age_range === $option)
                                            >
                                                {{ $option }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Who is this audience, in one line?</label>

                                    <input
                                        type="text"
                                        name="who"
                                        class="form-input"
                                        maxlength="80"
                                        value="{{ $answers['who'] ?? '' }}"
                                        required
                                    >
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Is the buyer the same as the user?</label>

                                    <select
                                        name="buyer_is_user"
                                        class="form-select"
                                        data-persona-buyer-select
                                        required
                                    >
                                        @foreach ([
                                            'Yes — they buy it and use it themselves',
                                            'No — they buy it for someone else',
                                            'No — someone else buys it for them',
                                        ] as $option)
                                            <option
                                                value="{{ $option }}"
                                                @selected(($answers['buyer_is_user'] ?? '') === $option)
                                            >
                                                {{ $option }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div
                                    class="form-group conditional-field"
                                    data-persona-decider-field
                                >
                                    <label class="form-label">Who actually decides or pays?</label>

                                    <input
                                        type="text"
                                        name="decider"
                                        class="form-input"
                                        maxlength="60"
                                        value="{{ $answers['decider'] ?? '' }}"
                                    >
                                </div>

                                <div class="form-group">
                                    <label class="form-label">What matters most to them?</label>

                                    <div class="checkbox-grid other-option">
                                        @foreach ($personaPriorities as $priority)
                                            <label class="checkbox-row">
                                                <input
                                                    type="checkbox"
                                                    name="priorities[]"
                                                    value="{{ $priority }}"
                                                    @checked(in_array($priority, $answers['priorities'] ?? [], true))
                                                >

                                                <span>{{ $priority }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>

                                <x-ai-assist-field
                                label="What makes them hesitate?"
                                name="objection"
                                :value="old('objection', $answers['objection'] ?? '')"
                                :questionKey="'persona_objection'"
                                :clientId="$client->id"
                                :max="150"
                                :disabled="false"
                                placeholder="e.g., They worry the quality won’t match the price, or they’ve been let down before."
                                footer="What stops them from buying — price worries, trust, habit? Optional."
                            />

                                <div class="modal-actions">
                                    <button class="btn btn-primary" type="submit">
                                        Save Persona
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
    <form
        method="POST"
        action="{{ route('agency.personas.store', $client) }}"
        data-persona-form
    >
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

            <select
                name="buyer_is_user"
                class="form-select"
                data-persona-buyer-select
                required
            >
                <option value="">Select answer</option>
                <option value="Yes — they buy it and use it themselves">Yes — they buy it and use it themselves</option>
                <option value="No — they buy it for someone else">No — they buy it for someone else</option>
                <option value="No — someone else buys it for them">No — someone else buys it for them</option>
            </select>
        </div>

        <div
            class="form-group conditional-field"
            data-persona-decider-field
        >
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
                @foreach ($personaPriorities as $priority)
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

        <x-ai-assist-field
        label="What makes them hesitate?"
        name="objection"
        :value="old('objection')"
        :questionKey="'persona_objection'"
        :clientId="$client->id"
        :max="150"
        :disabled="false"
        placeholder="e.g., They worry the quality won’t match the price, or they’ve been let down before."
        footer="What stops them from buying — price worries, trust, habit? Optional."
    />
    
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
<script>
    document.addEventListener('DOMContentLoaded', () => {
        /*
        |--------------------------------------------------------------------------
        | Persona buyer / decider conditional field
        |--------------------------------------------------------------------------
        */
    
        document
            .querySelectorAll('[data-persona-form]')
            .forEach((form) => {
                const buyerSelect =
                    form.querySelector('[data-persona-buyer-select]');
    
                const deciderField =
                    form.querySelector('[data-persona-decider-field]');
    
                const deciderInput =
                    deciderField?.querySelector('input');
    
                function refreshDeciderField() {
                    if (!buyerSelect || !deciderField) {
                        return;
                    }
    
                    const shouldShow =
                        buyerSelect.value &&
                        buyerSelect.value !== 'Yes — they buy it and use it themselves';
    
                    deciderField.classList.toggle('hidden', !shouldShow);
    
                    if (deciderInput) {
                        deciderInput.required = shouldShow;
    
                        if (!shouldShow) {
                            deciderInput.value = '';
                        }
                    }
                }
    
                buyerSelect?.addEventListener('change', refreshDeciderField);
    
                refreshDeciderField();
            });
    
        /*
        |--------------------------------------------------------------------------
        | AI Assist
        |--------------------------------------------------------------------------
        */
    
        const aiButtons =
            document.querySelectorAll('[data-open-ai-assist]');
    
        let activeFieldWrapper = null;
        let activeTextarea = null;
        let dailyLimitReached = false;
    
        function refreshAiButtons() {
            aiButtons.forEach((button) => {
                if (dailyLimitReached) {
                    button.disabled = true;
                    button.classList.add('disabled-ai');
                    button.title =
                        'Daily AI assist limit reached. Resets at midnight.';
                }
            });
        }
    
        aiButtons.forEach((button) => {
            button.addEventListener('click', async () => {
                if (button.disabled || dailyLimitReached) {
                    return;
                }
    
                activeFieldWrapper =
                    button.closest('[data-ai-field]');
    
                activeTextarea =
                    activeFieldWrapper?.querySelector('[data-ai-target-field]');
    
                if (!activeTextarea) {
                    alert('AI Assist field is not configured correctly.');
                    return;
                }
    
                const existingValue =
                    activeTextarea.value.trim();
    
                const skipConfirmation =
                    localStorage.getItem(
                        'markething_ai_assist_skip_replace_confirmation'
                    ) === 'true';
    
                if (existingValue.length > 0 && !skipConfirmation) {
                    const confirmed =
                        confirm(
                            'MARKETHING will replace the text currently written in this field. Continue?'
                        );
    
                    if (!confirmed) {
                        return;
                    }
    
                    const dontAskAgain =
                        confirm(
                            'Don’t ask me again before replacing existing AI Assist text?'
                        );
    
                    if (dontAskAgain) {
                        localStorage.setItem(
                            'markething_ai_assist_skip_replace_confirmation',
                            'true'
                        );
                    }
                }
    
                const originalText =
                    button.textContent;
    
                button.disabled = true;
                button.textContent = 'Generating...';
                activeTextarea.readOnly = true;
    
                try {
                    showAiLoading(
                        'Drafting Answer...',
                        'MARKETHING is generating a response using your Business Context.'
                    );
    
                    const csrfToken =
                        document
                            .querySelector('meta[name="csrf-token"]')
                            ?.getAttribute('content') || '{{ csrf_token() }}';
    
                    const response =
                        await fetch('{{ route('agency.ai-assist') }}', {
                            method: 'POST',
                            credentials: 'same-origin',
    
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                'X-Requested-With': 'XMLHttpRequest',
                            },
    
                            body: JSON.stringify({
                                ...(button.dataset.clientId
                                    ? { client_id: button.dataset.clientId }
                                    : {}),
    
                                question_key:
                                    button.dataset.questionKey,
    
                                question_label:
                                    button.dataset.aiLabel,
    
                                input:
                                    '',
    
                                character_limit:
                                    button.dataset.characterLimit,
    
                                extra_instructions:
                                    '',
    
                                business_context:
                                    @json($client->business_context ?? ''),
    
                                business_info:
                                    @json($client->business_info ?? []),
    
                                brand_info:
                                    @json($client->brand_info ?? []),
                            }),
                        });
    
                    const data =
                        await response.json();
    
                    if (response.status === 429) {
                        dailyLimitReached = true;
                        refreshAiButtons();
    
                        alert(
                            data.message ||
                            'Daily AI assist limit reached. Resets at midnight.'
                        );
    
                        return;
                    }
    
                    if (!response.ok || !data.success) {
                        alert(
                            data.message ||
                            'Couldn’t draft an answer. Try again in a moment.'
                        );
    
                        return;
                    }
    
                    activeTextarea.value =
                        data.text;
    
                    const counter =
                        activeFieldWrapper.querySelector('[data-character-counter]');
    
                    if (counter) {
                        counter.textContent =
                            activeTextarea.value.length +
                            '/' +
                            button.dataset.characterLimit;
                    }
    
                    activeTextarea.dispatchEvent(
                        new Event('input', {
                            bubbles: true,
                        })
                    );
    
                } catch (error) {
                    alert(
                        'Couldn’t draft an answer. Try again in a moment.'
                    );
                } finally {
                    hideAiLoading();
    
                    button.disabled = false;
                    button.textContent = originalText;
                    activeTextarea.readOnly = false;
    
                    refreshAiButtons();
                }
            });
        });
    });
    </script>
@endsection

