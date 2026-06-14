@extends('layouts.dashboard')

@section('title', 'Create Campaign - MARKETHING')

@section('page-title', 'Create Campaign')

@section(
    'page-subtitle',
    'Generate AI-powered campaigns using real client profiles and personas.'
)

@section('user-name', auth()->user()->name ?? 'Agency User')
@section('user-role', 'Agency Account')

@section('dashboard-content')

<div class="campaign-builder-page">

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
    id="campaignCreateForm"
    method="POST"
    action="{{ route('agency.campaigns.store') }}"
    class="campaign-builder-layout"
    novalidate
>

        @csrf

        <div class="campaign-main-column">

            <div class="table-card">

                <div class="section-header">
                    <div>
                        <h2 class="section-title">
                            Campaign Information
                        </h2>

                        <p class="section-description">
                            Configure the core details of the generated campaign.
                        </p>
                    </div>
                </div>

                <div class="form-grid">

                    <div class="form-group">

                        <label class="form-label">
                            Campaign Name
                        </label>

                        <input
                            type="text"
                            name="name"
                            class="form-input"
                            placeholder="Summer Product Launch"
                            value="{{ old('name') }}"
                            required
                        >

                    </div>

                    <div class="form-group">

                        <label class="form-label">
                            Campaign Objective
                        </label>

                        <input
                            type="text"
                            name="objective"
                            class="form-input"
                            placeholder="Increase awareness and engagement"
                            value="{{ old('objective') }}"
                            required
                        >

                    </div>

                </div>

                <div class="form-group">

                    <label class="form-label">
                        Campaign Description
                    </label>

                    <textarea
                        name="description"
                        class="form-textarea"
                        placeholder="Describe campaign goals, offers, themes, and important context..."
                    >{{ old('description') }}</textarea>

                </div>

            </div>

            <div class="table-card">

                <div class="section-header">
                    <div>
                        <h2 class="section-title">
                            Client & Persona
                        </h2>

                        <p class="section-description">
                            Select the business and target audience persona.
                        </p>
                    </div>
                </div>

                <div class="form-grid">

                    <div class="form-group">

                        <label class="form-label">
                            Client Profile
                        </label>

                        <select
                            name="client_id"
                            id="clientSelect"
                            class="form-input"
                            required
                        >

                            <option value="">
                                Select client
                            </option>

                            @foreach ($clients as $client)

                                <option
                                    value="{{ $client->id }}"
                                    data-personas='@json($client->personas)'
                                    {{ old('client_id') == $client->id ? 'selected' : '' }}
                                >
                                    {{ $client->name }}
                                </option>

                            @endforeach

                        </select>

                    </div>

                    <div class="form-group">

                        <label class="form-label">
                            Persona
                        </label>

                        <select
                            name="persona_id"
                            id="personaSelect"
                            class="form-input"
                            required
                        >

                            <option value="">
                                Select persona
                            </option>

                        </select>

                    </div>

                </div>

            </div>

            <div class="table-card">

                <div class="section-header">
                    <div>
                        <h2 class="section-title">
                            Scheduling
                        </h2>

                        <p class="section-description">
                            Configure campaign duration, channels, and post count.
                        </p>
                    </div>
                </div>

                <div class="form-grid">

                    <div class="form-group">

                        <label class="form-label">
                            Start Date
                        </label>

                        <input
                            type="date"
                            name="start_date"
                            class="form-input"
                            value="{{ old('start_date') }}"
                            required
                        >

                    </div>

                    <div class="form-group">

                        <label class="form-label">
                            End Date
                        </label>

                        <input
                            type="date"
                            name="end_date"
                            class="form-input"
                            value="{{ old('end_date') }}"
                            required
                        >

                    </div>

                </div>

                <div class="form-group">

                    <label class="form-label">
                        Channels
                    </label>

                    <div class="checkbox-grid">

                        <label class="channel-checkbox">

                            <input
                                type="checkbox"
                                name="channels[]"
                                value="instagram"
                                {{ in_array('instagram', old('channels', [])) ? 'checked' : '' }}
                            >

                            <span>Instagram</span>

                        </label>

                        <label class="channel-checkbox">

                            <input
                                type="checkbox"
                                name="channels[]"
                                value="facebook"
                                {{ in_array('facebook', old('channels', [])) ? 'checked' : '' }}
                            >

                            <span>Facebook</span>

                        </label>

                    </div>

                </div>

                <div class="form-group">

                    <label class="form-label">
                        Requested Posts Count
                    </label>

                    <input
                        type="number"
                        name="requested_posts_count"
                        class="form-input"
                        min="1"
                        placeholder="12"
                        value="{{ old('requested_posts_count') }}"
                        required
                    >


                </div>

            </div>

        </div>

        <div class="campaign-side-column">

            <div class="table-card sticky-card">

                <h2 class="section-title">
                    AI Campaign Generation
                </h2>

                <div class="completion-list">

                    <div class="completion-item done">
                        <span>✓</span>
                        Business Context
                    </div>

                    <div class="completion-item done">
                        <span>✓</span>
                        Persona Selection
                    </div>

                    <div class="completion-item active">
                        <span>•</span>
                        Campaign Generation
                    </div>

                </div>

                <div class="profile-side-divider"></div>

                <div class="campaign-note">

                    <h3>Generation Notes</h3>

                    <p>
                        MARKETHING will assemble:
                    </p>

                    <ul>
                        <li>Business context</li>
                        <li>Brand information</li>
                        <li>Audience persona</li>
                        <li>Campaign objective</li>
                        <li>Selected channels</li>
                    </ul>

                </div>

                <div class="save-actions">

                    <button
                    id="generateCampaignBtn"
                    class="btn btn-primary"
                    type="submit"
                >
                    Generate Campaign
                </button>

                    <a
                        href="{{ route('agency.dashboard') }}"
                        class="btn btn-secondary full-btn"
                    >
                        Cancel
                    </a>

                </div>

            </div>

        </div>

    </form>

</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('campaignCreateForm');
        const generateButton = document.getElementById('generateCampaignBtn');
    
        const clientSelect = document.getElementById('clientSelect');
        const personaSelect = document.getElementById('personaSelect');
    
        let formChanged = false;
        let formSubmitted = false;
    
        if (generateButton) {
            generateButton.disabled = false;
            generateButton.textContent = 'Generate Campaign';
        }
    
        function populatePersonas() {
            personaSelect.innerHTML = `
                <option value="">
                    Select persona
                </option>
            `;
    
            const selectedOption =
                clientSelect.options[clientSelect.selectedIndex];
    
            if (!selectedOption || !selectedOption.dataset.personas) {
                return;
            }
    
            const personas =
                JSON.parse(selectedOption.dataset.personas);
    
            personas.forEach((persona) => {
                const option = document.createElement('option');
    
                option.value = persona.id;
                option.textContent = persona.name;
    
                if ("{{ old('persona_id') }}" == persona.id) {
                    option.selected = true;
                }
    
                personaSelect.appendChild(option);
            });
        }
    
        populatePersonas();
    
        clientSelect?.addEventListener('change', populatePersonas);
    
        form?.querySelectorAll('input, textarea, select').forEach((field) => {
            field.addEventListener('input', () => {
                formChanged = true;
            });
    
            field.addEventListener('change', () => {
                formChanged = true;
            });
        });
    
        function validateCampaignForm() {
            const name = form.querySelector('[name="name"]')?.value.trim();
            const objective = form.querySelector('[name="objective"]')?.value.trim();
            const clientId = form.querySelector('[name="client_id"]')?.value;
            const personaId = form.querySelector('[name="persona_id"]')?.value;
            const startDate = form.querySelector('[name="start_date"]')?.value;
            const endDate = form.querySelector('[name="end_date"]')?.value;
            const postsCount = form.querySelector('[name="requested_posts_count"]')?.value;
            const channels = form.querySelectorAll('[name="channels[]"]:checked');
    
            if (!name) return 'Please enter a campaign name.';
            if (!objective) return 'Please enter a campaign objective.';
            if (!clientId) return 'Please select a client profile.';
            if (!personaId) return 'Please select a persona.';
            if (!startDate) return 'Please select a start date.';
            if (!endDate) return 'Please select an end date.';
            if (endDate < startDate) return 'End date cannot be before the start date.';
            if (channels.length === 0) return 'Please select at least one channel.';
            if (!postsCount || Number(postsCount) < 1) return 'Please enter the requested posts count.';
    
            return null;
        }
    
        form?.addEventListener('submit', (event) => {
            event.preventDefault();
    
            const error = validateCampaignForm();
    
            if (error) {
                alert(error);
                return;
            }
    
            formSubmitted = true;
    
            if (generateButton) {
                generateButton.disabled = true;
                generateButton.textContent = 'Generating...';
            }
    
            showAiLoading(
                'Generating Campaign...',
                'MARKEthing is building your campaign posts. Please do not refresh or click back.'
            );
    
            form.submit();
        });
    
        window.addEventListener('beforeunload', (event) => {
            if (formChanged && !formSubmitted) {
                event.preventDefault();
                event.returnValue = '';
            }
        });
    
        document.querySelectorAll('a[href]').forEach((link) => {
            link.addEventListener('click', (event) => {
                const href = link.getAttribute('href');
    
                if (!href || href.startsWith('#') || href.startsWith('javascript:')) {
                    return;
                }
    
                if (formChanged && !formSubmitted) {
                    const confirmed = confirm(
                        'You have unsaved campaign changes. Leave this page?'
                    );
    
                    if (!confirmed) {
                        event.preventDefault();
                    }
                }
            });
        });
    });
    </script>
    
@endsection