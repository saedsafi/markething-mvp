@extends('layouts.dashboard')

@section('title', isset($isEditing) ? 'Edit Client - MARKETHING' : 'Create Client - MARKETHING')

@section('page-title', isset($isEditing) ? 'Edit Client Profile' : 'Create Client Profile')

@section(
    'page-subtitle',
    isset($isEditing)
        ? 'Update business context, brand identity, and personas.'
        : 'Create a structured business profile for AI-powered campaign generation.'
)

@section('user-name', auth()->user()->name ?? 'Nova Marketing')
@section('user-role', 'Agency Account')

@section('dashboard-content')

@php
    $editing = isset($isEditing) && isset($client);

    $businessInfo = $client->business_info ?? [];
    $brandInfo = $client->brand_info ?? [];

    $primaryPersona = $editing
        ? $client->personas->first()
        : null;

    $personaAnswers = $primaryPersona?->answers ?? [];

    $businessContextMax = app(\App\Services\AppSettingService::class)
    ->int('business_context_character_limit', 5000);

    $businessContextValue = old('business_context', $client->business_context ?? '');

    $aiDisabled = false;
    @endphp

<div class="client-create-page">

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

    <form
        method="POST"
        action="{{ $editing
            ? route('agency.clients.update', $client)
            : route('agency.clients.store') }}"
        class="client-form-layout"
    >
        @csrf

        @if ($editing)
            @method('PATCH')
        @endif

        <div class="form-main-column">

            <div class="table-card">

                <div class="section-header">
                    <div>
                        <h2 class="section-title">Business Information</h2>

                        <p class="section-description">
                            Core information about the business and its market positioning.
                        </p>
                    </div>
                </div>

                <div class="form-grid">

                    <div class="form-group">
                        <label class="form-label">Business Name</label>

                        <input
                            type="text"
                            name="name"
                            class="form-input"
                            placeholder="Bloom Café"
                            value="{{ old('name', $client->name ?? '') }}"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label class="form-label">Industry</label>

                        <input
                            type="text"
                            name="industry"
                            class="form-input"
                            placeholder="Coffee Shop"
                            value="{{ old('industry', $client->industry ?? '') }}"
                        >
                    </div>

                </div>

                <div class="form-group">

                    <label class="form-label">
                        Business Context
                    </label>

                    <textarea
                        name="business_context"
                        class="form-textarea"
                        maxlength="{{ $businessContextMax }}"
                        data-business-context-source
                        placeholder="Describe the business, products, services, audience, goals, and unique positioning..."
                    >{{ old('business_context', $client->business_context ?? '') }}</textarea>

                    <div class="ai-field-footer">
                        <p>
                            This is the source context AI Assist uses for the fields below.
                        </p>

                        <span data-business-context-counter>
                            {{ mb_strlen(old('business_context', $client->business_context ?? '')) }}/{{ $businessContextMax }}
                        </span>
                    </div>

                </div>

                <x-ai-assist-field
                    label="Business Offer"
                    name="business_offer"
                    :value="old('business_offer', $businessInfo['business_offer'] ?? '')"
                    question-key="business_offer"
                    :client-id="$editing ? $client->id : null"
                    :max="5000"
                    :disabled="$aiDisabled"
                    placeholder="What does this business offer?"
                    footer="AI Assist uses the Business Context above to draft this answer."
                />

            </div>

            <div class="table-card">

                <div class="section-header">
                    <div>
                        <h2 class="section-title">Brand Information</h2>

                        <p class="section-description">
                            Define how the brand should sound and feel in generated campaigns.
                        </p>
                    </div>
                </div>

                <div class="form-grid">

                    <div class="form-group">
                        <label class="form-label">Brand Voice</label>

                        <input
                            type="text"
                            name="brand_voice"
                            class="form-input"
                            placeholder="Modern, playful, minimal"
                            value="{{ old('brand_voice', $brandInfo['brand_voice'] ?? '') }}"
                        >
                    </div>

                    <div class="form-group">
                        <label class="form-label">Brand Values</label>

                        <input
                            type="text"
                            name="brand_values"
                            class="form-input"
                            placeholder="Authenticity, creativity, wellness"
                            value="{{ old('brand_values', $brandInfo['brand_values'] ?? '') }}"
                        >
                    </div>

                </div>

                <x-ai-assist-field
                    label="Brand Personality"
                    name="brand_personality"
                    :value="old('brand_personality', $brandInfo['brand_personality'] ?? '')"
                    question-key="brand_personality"
                    :client-id="$editing ? $client->id : null"
                    :max="5000"
                    :disabled="$aiDisabled"
                    placeholder="Describe how the brand behaves and communicates."
                    footer="AI Assist uses the Business Context above to draft this answer."
                />

            </div>

            <div class="table-card">

                <div class="section-header">
                    <div>
                        <h2 class="section-title">Initial Persona</h2>

                        <p class="section-description">
                            Create the first target audience persona for this business.
                        </p>
                    </div>
                </div>

                <div class="form-grid">

                    <div class="form-group">
                        <label class="form-label">Persona Name</label>

                        <input
                            type="text"
                            name="persona_name"
                            class="form-input"
                            placeholder="Young Professionals"
                            value="{{ old('persona_name', $primaryPersona->name ?? '') }}"
                        >
                    </div>

                    <div class="form-group">
                        <label class="form-label">Age Range</label>

                        <input
                            type="text"
                            name="persona_age_range"
                            class="form-input"
                            placeholder="25 - 35"
                            value="{{ old('persona_age_range', $primaryPersona->age_range ?? '') }}"
                        >
                    </div>

                </div>

                <x-ai-assist-field
                    label="Persona Description"
                    name="persona_description"
                    :value="old('persona_description', $personaAnswers['description'] ?? '')"
                    question-key="persona_description"
                    :client-id="$editing ? $client->id : null"
                    :max="5000"
                    :disabled="$aiDisabled"
                    placeholder="Describe interests, lifestyle, behavior, and motivations."
                    footer="AI Assist uses the Business Context above to draft this persona description."
                />

            </div>

        </div>

        <div class="form-side-column">

            <div class="table-card sticky-card">

                <h2 class="section-title">Profile Completion</h2>

                <div class="completion-list">

                    <div class="completion-item done">
                        <span>✓</span>
                        Business Details
                    </div>

                    <div class="completion-item active">
                        <span>•</span>
                        Brand Identity
                    </div>

                    <div class="completion-item">
                        <span>•</span>
                        Audience Persona
                    </div>

                </div>

                <div class="save-actions">

                    <button class="btn btn-primary full-btn" type="submit">
                        @if ($editing)
                            Save Client Changes
                        @else
                            Create Client Profile
                        @endif
                    </button>

                    <a
                        href="{{ route('agency.clients.index') }}"
                        class="btn btn-secondary full-btn"
                    >
                        Cancel
                    </a>

                </div>

            </div>

        </div>

    </form>

</div>

<x-modal
    id="aiAssistModal"
    title="AI Assist"
    subtitle="Add optional details to help MARKETHING draft a better answer."
>
    <div class="form-group">
        <label class="form-label" id="aiAssistLabel">
            Field
        </label>

        <p class="input-helper" id="aiAssistHelper">
            Add extra details if needed.
        </p>

        <textarea
            class="form-textarea"
            id="aiAssistExtraInput"
            rows="5"
            placeholder="Optional extra context..."
        ></textarea>
    </div>

    <div class="modal-actions">
        <button class="btn btn-primary" type="button" id="runAiAssistBtn">
            ✦ Generate Draft
        </button>

        <button class="btn btn-secondary" type="button" data-close-modal>
            Cancel
        </button>
    </div>
</x-modal>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const businessContext = document.querySelector('[data-business-context-source]');
        const businessCounter = document.querySelector('[data-business-context-counter]');
        const aiButtons = document.querySelectorAll('[data-open-ai-assist]');
    
        let dailyLimitReached = false;
    
        function refreshAiButtons() {
            const hasContext =
                businessContext &&
                businessContext.value.trim().length > 0;
    
            aiButtons.forEach((button) => {
                button.disabled =
                    !hasContext || dailyLimitReached;
    
                if (!hasContext) {
                    button.classList.add('disabled-ai');
                    button.title =
                        'Add a description of the business at the top of the profile to enable AI Assist.';
                } else if (dailyLimitReached) {
                    button.classList.add('disabled-ai');
                    button.title =
                        'Daily AI assist limit reached. Resets at midnight.';
                } else {
                    button.classList.remove('disabled-ai');
                    button.title = '';
                }
            });
        }
    
        if (businessContext && businessCounter) {
            businessContext.addEventListener('input', () => {
                businessCounter.textContent =
                    businessContext.value.length + '/' + businessContext.getAttribute('maxlength');
    
                refreshAiButtons();
            });
        }
    
        refreshAiButtons();
    
        let activeFieldWrapper = null;
        let activeTextarea = null;
    
        const modal = document.getElementById('aiAssistModal');
        const labelEl = document.getElementById('aiAssistLabel');
        const helperEl = document.getElementById('aiAssistHelper');
        const extraInput = document.getElementById('aiAssistExtraInput');
        const runBtn = document.getElementById('runAiAssistBtn');
    
        aiButtons.forEach((button) => {
            button.addEventListener('click', () => {
                if (button.disabled) {
                    return;
                }
    
                activeFieldWrapper = button.closest('[data-ai-field]');
                activeTextarea = activeFieldWrapper.querySelector('[data-ai-target-field]');
    
                labelEl.textContent =
                    button.dataset.aiLabel || 'AI Assist';
    
                helperEl.textContent =
                    button.dataset.aiHelper || 'Add extra details if needed.';
    
                extraInput.value = '';
    
                modal.classList.add('active');
            });
        });
    
        runBtn?.addEventListener('click', async () => {
            if (!activeFieldWrapper || !activeTextarea) {
                return;
            }
    
            const existingValue =
                activeTextarea.value.trim();
    
            if (existingValue.length > 0) {
                const confirmed = confirm(
                    'This will replace your current text. Continue?'
                );
    
                if (!confirmed) {
                    return;
                }
            }
    
            const button =
                activeFieldWrapper.querySelector('[data-open-ai-assist]');
    
            const warning =
                activeFieldWrapper.querySelector('[data-ai-soft-warning]');
    
            const clicks =
                Number(activeTextarea.dataset.aiCurrentClicks || 0) + 1;
    
            activeTextarea.dataset.aiCurrentClicks =
                clicks;
    
            if (clicks >= 3 && warning) {
                warning.classList.remove('hidden');
            }
    
            button.disabled = true;
            runBtn.disabled = true;
            runBtn.textContent = 'Generating...';
            activeTextarea.readOnly = true;
    
            try {
                const csrfToken =
                    document
                        .querySelector('meta[name="csrf-token"]')
                        ?.getAttribute('content') || '{{ csrf_token() }}';

                const response = await fetch('{{ route('agency.ai-assist') }}', {
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
                            extraInput.value,
    
                        character_limit:
                            button.dataset.characterLimit,
    
                        extra_instructions:
                            extraInput.value,
    
                        business_context:
                            businessContext?.value || '',
    
                        business_info: {
                            business_offer:
                                document.querySelector('[name="business_offer"]')?.value || '',
                        },
    
                        brand_info: {
                            brand_voice:
                                document.querySelector('[name="brand_voice"]')?.value || '',
    
                            brand_values:
                                document.querySelector('[name="brand_values"]')?.value || '',
    
                            brand_personality:
                                document.querySelector('[name="brand_personality"]')?.value || '',
                        },
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
    
                modal.classList.remove('active');
    
            } catch (error) {
                alert(
                    'Couldn’t draft an answer. Try again in a moment.'
                );

                alert(
                    error.message
                );
            } finally {
                runBtn.disabled = false;
                runBtn.textContent = '✦ Generate Draft';
                activeTextarea.readOnly = false;
    
                refreshAiButtons();
            }
        });
    
        document.querySelectorAll('[data-ai-target-field]').forEach((textarea) => {
            const wrapper =
                textarea.closest('[data-ai-field]');
    
            const counter =
                wrapper?.querySelector('[data-character-counter]');
    
            const max =
                textarea.getAttribute('maxlength');
    
            textarea.addEventListener('input', () => {
                if (counter) {
                    counter.textContent =
                        textarea.value.length + '/' + max;
                }
            });
        });
    });
    </script>
@endsection