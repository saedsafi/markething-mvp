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

    $steps = [
        'Business Details',
        'Business Context',
        'Business Offer',
        'Brand Basics',
        'Brand Personality',
        'Persona Basics',
        'Persona Description',
    ];
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
        class="client-form-layout staged-client-form"
    >
        @csrf

        @if ($editing)
            @method('PATCH')
        @endif

        <div class="form-main-column">

            <div class="table-card">

                <div class="section-header">
                    <div>
                        <span class="hero-badge">
                            Step-by-step profile
                        </span>

                        <h2 class="section-title" data-current-step-title>
                            Business Details
                        </h2>

                        <p class="section-description" data-current-step-description>
                            Start with the basic business identity.
                        </p>
                    </div>
                </div>

                <div class="step-progress-bar">
                    <div
                        class="step-progress-fill"
                        data-step-progress-fill
                    ></div>
                </div>

                {{-- STEP 1 --}}
                <div
                    class="client-step-panel active"
                    data-client-step="0"
                    data-step-title="Business Details"
                    data-step-description="Start with the basic business identity."
                >
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
                </div>

                {{-- STEP 2 --}}
                <div
                    class="client-step-panel"
                    data-client-step="1"
                    data-step-title="Business Context"
                    data-step-description="Paste the main business context AI Assist will use later."
                >
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
                        >{{ $businessContextValue }}</textarea>

                        <div class="ai-field-footer">
                            <p>
                                This is the source context AI Assist uses for the fields below.
                            </p>

                            <span data-business-context-counter>
                                {{ mb_strlen($businessContextValue) }}/{{ $businessContextMax }}
                            </span>
                        </div>

                        <details class="business-context-examples">
                            <summary>
                                Examples of what to paste
                            </summary>

                            <div class="examples-box">
                                <p>
                                    Example: “We are a local coffee shop serving specialty coffee, pastries, and quiet workspace seating for students and remote workers.”
                                </p>

                                <p>
                                    Example: “Our brand sells handmade gold jewelry for women who want elegant gifts, bridal sets, and everyday luxury pieces.”
                                </p>

                                <p>
                                    Example: “We are a burger restaurant focused on juicy chicken and beef burgers, fresh toppings, and fast delivery.”
                                </p>
                            </div>
                        </details>

                    </div>
                </div>

                {{-- STEP 3 --}}
                <div
                    class="client-step-panel"
                    data-client-step="2"
                    data-step-title="Business Offer"
                    data-step-description="Describe what this business offers to customers."
                >
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

                {{-- STEP 4 --}}
                <div
                    class="client-step-panel"
                    data-client-step="3"
                    data-step-title="Brand Basics"
                    data-step-description="Define the basic tone and values of the brand."
                >
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
                </div>

                {{-- STEP 5 --}}
                <div
                    class="client-step-panel"
                    data-client-step="4"
                    data-step-title="Brand Personality"
                    data-step-description="Describe how the brand behaves and communicates."
                >
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

                {{-- STEP 6 --}}
                <div
                    class="client-step-panel"
                    data-client-step="5"
                    data-step-title="Persona Basics"
                    data-step-description="Create the first target audience persona."
                >
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
                </div>

                {{-- STEP 7 --}}
                <div
                    class="client-step-panel"
                    data-client-step="6"
                    data-step-title="Persona Description"
                    data-step-description="Describe interests, lifestyle, behavior, and motivations."
                >
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

                <div class="client-step-actions">

                    <button
                        class="btn btn-secondary"
                        type="button"
                        data-prev-step
                    >
                        Back
                    </button>

                    <div class="client-step-actions-right">

                        <a
                            href="{{ route('agency.clients.index') }}"
                            class="btn btn-secondary"
                            data-unsaved-leave-link
                        >
                            Cancel
                        </a>

                        <button
                            class="btn btn-primary"
                            type="button"
                            data-next-step
                        >
                            Next
                        </button>

                        <button
                            class="btn btn-primary"
                            type="submit"
                            data-submit-client
                        >
                            @if ($editing)
                                Save Client Changes
                            @else
                                Create Client Profile
                            @endif
                        </button>

                    </div>

                </div>

            </div>

        </div>

        <div class="form-side-column">

            <div class="table-card sticky-card">

                <h2 class="section-title">Profile Completion</h2>

                <div class="step-mini-progress">
                    <span data-step-counter>
                        Step 1 of {{ count($steps) }}
                    </span>
                </div>

                <div class="completion-list">

                    @foreach ($steps as $index => $step)
                        <div
                            class="completion-item {{ $index === 0 ? 'active' : '' }}"
                            data-step-progress-item
                        >
                            <span>
                                {{ $index === 0 ? '•' : $index + 1 }}
                            </span>

                            {{ $step }}
                        </div>
                    @endforeach

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
<x-modal
    id="replaceConfirmationModal"
    title="Replace Existing Text"
    subtitle="This action will overwrite the current field content."
>
    <div class="replace-confirmation-warning">
        <span class="replace-confirmation-warning-icon">
            !
        </span>

        <div>
            <p class="replace-confirmation-text">
                MARKETHING will generate a new draft and replace the text currently written in this field.
            </p>

            <p class="input-helper">
                You can still edit the generated answer manually after it appears.
            </p>
        </div>
    </div>

    <label class="checkbox-row replace-checkbox">
        <input
            type="checkbox"
            id="replaceDontAskAgain"
        >

        <span>
            Don't ask me again
        </span>
    </label>

    <div class="modal-actions">
        <button
            class="btn btn-secondary"
            type="button"
            id="cancelReplaceBtn"
        >
            Cancel
        </button>

        <button
            class="btn btn-primary"
            type="button"
            id="confirmReplaceBtn"
        >
            Continue
        </button>
    </div>
</x-modal>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const businessContext = document.querySelector('[data-business-context-source]');
        const businessCounter = document.querySelector('[data-business-context-counter]');
        const aiButtons = document.querySelectorAll('[data-open-ai-assist]');
        const form = document.querySelector('.staged-client-form');
    
        let formChanged = false;
        let formSubmitted = false;
        let dailyLimitReached = false;
    
        /*
        |--------------------------------------------------------------------------
        | Unsaved Changes Protection
        |--------------------------------------------------------------------------
        */
    
        form?.querySelectorAll('input, textarea, select').forEach((field) => {
            field.addEventListener('input', () => {
                formChanged = true;
            });
    
            field.addEventListener('change', () => {
                formChanged = true;
            });
        });
    
        form?.addEventListener('submit', () => {
            formSubmitted = true;
        });
    
        window.addEventListener('beforeunload', (event) => {
            if (formChanged && !formSubmitted) {
                event.preventDefault();
                event.returnValue = '';
            }
        });
    
        document
            .querySelectorAll('[data-unsaved-leave-link]')
            .forEach((link) => {
                link.addEventListener('click', (event) => {
                    const href = link.getAttribute('href');
    
                    if (
                        !href ||
                        href.startsWith('#') ||
                        href.startsWith('javascript:')
                    ) {
                        return;
                    }
    
                    if (formChanged && !formSubmitted) {
                        const confirmed = confirm(
                            'You have unsaved changes. Leave this page?'
                        );
    
                        if (!confirmed) {
                            event.preventDefault();
                        }
                    }
                });
            });
    
        /*
        |--------------------------------------------------------------------------
        | AI Assist Enable / Disable
        |--------------------------------------------------------------------------
        */
    
        function refreshAiButtons() {
            const hasContext =
                businessContext &&
                businessContext.value.trim().length > 0;
    
            aiButtons.forEach((button) => {
                button.disabled = !hasContext || dailyLimitReached;
    
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
                    businessContext.value.length +
                    '/' +
                    businessContext.getAttribute('maxlength');
    
                refreshAiButtons();
            });
        }
    
        refreshAiButtons();
    
        /*
        |--------------------------------------------------------------------------
        | Step Wizard
        |--------------------------------------------------------------------------
        */
    
        const steps = document.querySelectorAll('[data-client-step]');
        const nextBtn = document.querySelector('[data-next-step]');
        const prevBtn = document.querySelector('[data-prev-step]');
        const submitBtn = document.querySelector('[data-submit-client]');
        const progressFill = document.querySelector('[data-step-progress-fill]');
        const progressItems = document.querySelectorAll('[data-step-progress-item]');
        const stepCounter = document.querySelector('[data-step-counter]');
        const currentStepTitle = document.querySelector('[data-current-step-title]');
        const currentStepDescription = document.querySelector('[data-current-step-description]');
    
        let currentStep = 0;
    
        function showStep(index) {
            steps.forEach((step, i) => {
                step.classList.toggle('active', i === index);
            });
    
            progressItems.forEach((item, i) => {
                const icon = item.querySelector('span');
    
                item.classList.toggle('done', i < index);
                item.classList.toggle('active', i === index);
    
                if (icon) {
                    if (i < index) {
                        icon.textContent = '✓';
                    } else if (i === index) {
                        icon.textContent = '•';
                    } else {
                        icon.textContent = i + 1;
                    }
                }
            });
    
            const activePanel = steps[index];
    
            if (currentStepTitle && activePanel) {
                currentStepTitle.textContent =
                    activePanel.dataset.stepTitle || '';
            }
    
            if (currentStepDescription && activePanel) {
                currentStepDescription.textContent =
                    activePanel.dataset.stepDescription || '';
            }
    
            if (progressFill) {
                progressFill.style.width =
                    (((index + 1) / steps.length) * 100) + '%';
            }
    
            if (stepCounter) {
                stepCounter.textContent =
                    'Step ' + (index + 1) + ' of ' + steps.length;
            }
    
            if (prevBtn) {
                prevBtn.style.display =
                    index === 0 ? 'none' : 'inline-flex';
            }
    
            if (nextBtn) {
                nextBtn.style.display =
                    index === steps.length - 1 ? 'none' : 'inline-flex';
            }
    
            if (submitBtn) {
                submitBtn.style.display =
                    index === steps.length - 1 ? 'inline-flex' : 'none';
            }
    
            window.scrollTo({
                top: 0,
                behavior: 'smooth',
            });
        }
    
        nextBtn?.addEventListener('click', () => {
            if (currentStep < steps.length - 1) {
                currentStep++;
                showStep(currentStep);
            }
        });
    
        prevBtn?.addEventListener('click', () => {
            if (currentStep > 0) {
                currentStep--;
                showStep(currentStep);
            }
        });
    
        showStep(currentStep);
    
        /*
        |--------------------------------------------------------------------------
        | AI Assist Modal
        |--------------------------------------------------------------------------
        */
    
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
    
                activeFieldWrapper =
                    button.closest('[data-ai-field]');
    
                activeTextarea =
                    activeFieldWrapper.querySelector('[data-ai-target-field]');
    
                labelEl.textContent =
                    button.dataset.aiLabel || 'AI Assist';
    
                helperEl.textContent =
                    button.dataset.aiHelper || 'Add extra details if needed.';
    
                extraInput.value = '';
    
                modal.classList.add('active');
                modal.classList.add('show');
            });
        });
    
        function shouldSkipReplaceConfirmation() {
            return localStorage.getItem(
                'markething_ai_assist_skip_replace_confirmation'
            ) === 'true';
        }
    
        function askBeforeReplacing() {
            return new Promise((resolve) => {
                const replaceModal = document.getElementById('replaceConfirmationModal');
                const confirmBtn = document.getElementById('confirmReplaceBtn');
                const cancelBtn = document.getElementById('cancelReplaceBtn');
                const checkbox = document.getElementById('replaceDontAskAgain');
    
                if (!replaceModal || !confirmBtn || !cancelBtn) {
                    resolve(
                        window.confirm(
                            'This will replace your current text. Continue?'
                        )
                    );
    
                    return;
                }
    
                if (checkbox) {
                    checkbox.checked = false;
                }
    
                replaceModal.classList.add('show');
                replaceModal.classList.add('active');
    
                const cleanup = () => {
                    replaceModal.classList.remove('show');
                    replaceModal.classList.remove('active');
    
                    confirmBtn.removeEventListener('click', onConfirm);
                    cancelBtn.removeEventListener('click', onCancel);
                };
    
                const onConfirm = () => {
                    if (checkbox?.checked) {
                        localStorage.setItem(
                            'markething_ai_assist_skip_replace_confirmation',
                            'true'
                        );
                    }
    
                    cleanup();
                    resolve(true);
                };
    
                const onCancel = () => {
                    cleanup();
                    resolve(false);
                };
    
                confirmBtn.addEventListener('click', onConfirm);
                cancelBtn.addEventListener('click', onCancel);
            });
        }
    
        runBtn?.addEventListener('click', async () => {
            if (!activeFieldWrapper || !activeTextarea) {
                return;
            }
    
            const existingValue =
                activeTextarea.value.trim();
    
            if (
                existingValue.length > 0 &&
                !shouldSkipReplaceConfirmation()
            ) {
                const confirmed =
                    await askBeforeReplacing();
    
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
                modal.classList.remove('active');
                modal.classList.remove('show');
    
                showAiLoading(
                    'Drafting Answer...',
                    'MARKETHING is generating a response using your Business Context.'
                );
    
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
    
                        question_key: button.dataset.questionKey,
                        question_label: button.dataset.aiLabel,
                        input: extraInput.value,
                        character_limit: button.dataset.characterLimit,
                        extra_instructions: extraInput.value,
                        business_context: businessContext?.value || '',
    
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
    
                formChanged = true;
    
                const counter =
                    activeFieldWrapper.querySelector('[data-character-counter]');
    
                if (counter) {
                    counter.textContent =
                        activeTextarea.value.length +
                        '/' +
                        button.dataset.characterLimit;
                }
    
            } catch (error) {
                alert(
                    'Couldn’t draft an answer. Try again in a moment.'
                );
            } finally {
                hideAiLoading();
    
                runBtn.disabled = false;
                runBtn.textContent = '✦ Generate Draft';
                activeTextarea.readOnly = false;
    
                refreshAiButtons();
            }
        });
    
        /*
        |--------------------------------------------------------------------------
        | Character Counters
        |--------------------------------------------------------------------------
        */
    
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