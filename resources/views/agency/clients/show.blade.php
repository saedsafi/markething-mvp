@extends('layouts.dashboard')

@section('title', 'Client Profile - MARKETHING')

@section('page-title', 'Bloom Café')
@section('page-subtitle', 'Client profile, personas, brand information, and campaign history.')

@section('user-name', 'Nova Marketing')
@section('user-role', 'Agency Account')

@section('dashboard-content')

<div class="client-show-page">

    <div class="client-profile-hero">

        <div class="client-profile-main">
            <div class="client-logo large">B</div>

            <div>
                <span class="hero-badge">Active Client</span>

                <h2>Bloom Café</h2>

                <p>
                    A warm neighborhood café focused on specialty coffee,
                    cozy gatherings, fresh desserts, and lifestyle experiences.
                </p>
            </div>
        </div>

        <div class="hero-actions">
            <button class="btn btn-primary" type="button" data-create-campaign>
                + Create Campaign
            </button>

            <button class="btn btn-secondary" type="button" data-edit-profile>
                Edit Profile
            </button>

            <button class="btn btn-danger" type="button" data-deactivate-client>
                Deactivate
            </button>
        </div>

    </div>

    <div class="stats-grid">

        <x-stats-card
            label="Campaigns"
            value="8"
            hint="Generated for this client"
        />

        <x-stats-card
            label="Personas"
            value="3/5"
            hint="2 remaining"
        />

        <x-stats-card
            label="Status"
            value="Active"
            hint="Available for new campaigns"
        />

    </div>

    <div class="client-detail-grid">

        <div class="client-detail-main">

            <div class="table-card">
                <h2 class="section-title">Business Context</h2>

                <p class="profile-text">
                    Bloom Café is a modern cozy café serving specialty coffee,
                    handmade desserts, breakfast items, and seasonal drinks.
                    The brand tone is warm, aesthetic, community-driven, and friendly.
                </p>
            </div>

            <div class="table-card">
                <h2 class="section-title">Business Info</h2>

                <div class="profile-info-grid">
                    <div>
                        <span>Industry</span>
                        <strong>Food & Beverage</strong>
                    </div>

                    <div>
                        <span>Business Type</span>
                        <strong>Local Café</strong>
                    </div>

                    <div>
                        <span>Main Offer</span>
                        <strong>Specialty coffee and desserts</strong>
                    </div>
                </div>
            </div>

            <div class="table-card">
                <h2 class="section-title">Brand Info</h2>

                <div class="profile-info-grid">
                    <div>
                        <span>Voice</span>
                        <strong>Warm and friendly</strong>
                    </div>

                    <div>
                        <span>Style</span>
                        <strong>Cozy, aesthetic, lifestyle</strong>
                    </div>

                    <div>
                        <span>Values</span>
                        <strong>Quality, comfort, community</strong>
                    </div>
                </div>
            </div>

            <div class="table-card">
                <div class="table-header">
                    <h2>Audience Personas</h2>

                    <button class="btn btn-secondary" type="button">
                        + Add Persona
                    </button>
                </div>

                <div class="personas-grid">

                    <x-persona-card
                        initials="YP"
                        name="Young Professional"
                        description="Works full-time, loves aesthetic cafés, and visits for coffee breaks."
                        age="25-35"
                        channel="Instagram"
                    />

                    <x-persona-card
                        initials="FS"
                        name="Friend Group Planner"
                        description="Looks for cozy places to meet friends, celebrate, or take photos."
                        age="20-30"
                        channel="Instagram"
                    />

                    <x-persona-card
                        initials="RF"
                        name="Remote Freelancer"
                        description="Needs a calm place to work, drink coffee, and spend a few hours."
                        age="24-40"
                        channel="Facebook"
                    />

                </div>
            </div>

        </div>

        <aside class="client-detail-side">

            <div class="table-card">
                <h2 class="section-title">Profile Status</h2>

                <div class="checklist">
                    <div class="checklist-item done">
                        <span>✓</span>
                        Business context complete
                    </div>

                    <div class="checklist-item done">
                        <span>✓</span>
                        Business info complete
                    </div>

                    <div class="checklist-item done">
                        <span>✓</span>
                        Brand info complete
                    </div>

                    <div class="checklist-item done">
                        <span>✓</span>
                        Personas added
                    </div>
                </div>
            </div>

            <div class="table-card">
                <h2 class="section-title">Snapshot Behavior</h2>

                <p class="profile-text">
                    Existing campaigns keep the client, brand, persona, and Business Context snapshot from the moment they were generated.
                    Later edits or deactivation do not change previous campaigns.
                </p>
            </div>

            <div class="table-card">
                <h2 class="section-title">Recent Campaigns</h2>

                <div class="mini-campaign-list">
                    <div>
                        <strong>Summer Launch</strong>
                        <span>14 posts</span>
                    </div>

                    <div>
                        <strong>Weekend Offers</strong>
                        <span>8 posts</span>
                    </div>

                    <div>
                        <strong>Brand Awareness</strong>
                        <span>10 posts</span>
                    </div>
                </div>
            </div>

        </aside>

    </div>

</div>

<x-toast
    id="appToast"
    title="Action Completed"
    message="The client profile action was completed successfully."
/>

@endsection