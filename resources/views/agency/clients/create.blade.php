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
                        <h2 class="section-title">
                            Business Information
                        </h2>

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
                        placeholder="Describe the business, products, services, audience, goals, and unique positioning..."
                    >{{ old('business_context', $client->business_context ?? '') }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        Business Offer
                    </label>

                    <textarea
                        name="business_offer"
                        class="form-textarea ai-target"
                        placeholder="What does this business offer?"
                    >{{ old('business_offer', $businessInfo['business_offer'] ?? '') }}</textarea>
                </div>

            </div>

            <div class="table-card">

                <div class="section-header">
                    <div>
                        <h2 class="section-title">
                            Brand Information
                        </h2>

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

                <div class="form-group">
                    <label class="form-label">
                        Brand Personality
                    </label>

                    <textarea
                        name="brand_personality"
                        class="form-textarea ai-target"
                        placeholder="Describe how the brand behaves and communicates."
                    >{{ old('brand_personality', $brandInfo['brand_personality'] ?? '') }}</textarea>
                </div>

            </div>

            <div class="table-card">

                <div class="section-header">
                    <div>
                        <h2 class="section-title">
                            Initial Persona
                        </h2>

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

                <div class="form-group">
                    <label class="form-label">
                        Persona Description
                    </label>

                    <textarea
                        name="persona_description"
                        class="form-textarea ai-target"
                        placeholder="Describe interests, lifestyle, behavior, and motivations."
                    >{{ old('persona_description', $personaAnswers['description'] ?? '') }}</textarea>
                </div>

            </div>

        </div>

        <div class="form-side-column">

            <div class="table-card sticky-card">

                <h2 class="section-title">
                    Profile Completion
                </h2>

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

@endsection