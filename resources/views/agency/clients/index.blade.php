@extends('layouts.dashboard')

@section('title', 'Clients - MARKETHING')

@section('page-title', 'Client Profiles')
@section('page-subtitle', 'Create and manage the businesses you generate campaigns for.')

@section('user-name', 'Nova Marketing')
@section('user-role', 'Agency Account')

@section('dashboard-content')

<div class="clients-page">

    <div class="clients-header-card">

        <div>
            <span class="hero-badge">6 client profiles used</span>

            <h2>Your Client Workspace</h2>

            <p>
                Each client profile stores business context, brand information, personas,
                and campaign-ready marketing details.
            </p>
        </div>

        <div class="hero-actions">
            <a href="{{ url('/agency/clients/create') }}" class="btn btn-primary">
                + Create Client Profile
            </a>
        </div>

    </div>

    <div class="stats-grid">

        <x-stats-card
            label="Active Clients"
            value="5"
            hint="Available for campaigns"
        />

        <x-stats-card
            label="Inactive Clients"
            value="1"
            hint="Deactivated profiles"
        />

        <x-stats-card
            label="Total Personas"
            value="14"
            hint="Across active clients"
        />

    </div>

    <div class="client-grid">

        <div class="client-card">

            <div class="client-card-top">
                <div class="client-logo">B</div>

                <div>
                    <h3>Bloom Café</h3>
                    <p>Coffee shop · Lifestyle brand</p>
                </div>
            </div>

            <div class="client-info-list">
                <div>
                    <span>Personas</span>
                    <strong>3</strong>
                </div>

                <div>
                    <span>Campaigns</span>
                    <strong>8</strong>
                </div>

                <div>
                    <span>Status</span>
                    <strong>Active</strong>
                </div>
            </div>

            <div class="client-actions">
                <a href="{{ url('/agency/clients/show') }}" class="mini-btn">View Profile</a>
                <button class="mini-btn" type="button" data-edit-profile>Edit</button>
                <button class="mini-btn danger" type="button" data-deactivate-client>Deactivate</button>
            </div>

        </div>

        <div class="client-card">

            <div class="client-card-top">
                <div class="client-logo">L</div>

                <div>
                    <h3>Luna Boutique</h3>
                    <p>Fashion · Women’s clothing</p>
                </div>
            </div>

            <div class="client-info-list">
                <div>
                    <span>Personas</span>
                    <strong>2</strong>
                </div>

                <div>
                    <span>Campaigns</span>
                    <strong>5</strong>
                </div>

                <div>
                    <span>Status</span>
                    <strong>Active</strong>
                </div>
            </div>

            <div class="client-actions">
                <a href="{{ url('/agency/clients/show') }}" class="mini-btn">View Profile</a>
                <button class="mini-btn" type="button" data-edit-profile>Edit</button>
                <button class="mini-btn danger" type="button" data-deactivate-client>Deactivate</button>
            </div>

        </div>

        <div class="client-card incomplete">

            <div class="client-card-top">
                <div class="client-logo">N</div>

                <div>
                    <h3>Nova Fitness</h3>
                    <p>Gym · Health and wellness</p>
                </div>
            </div>

            <div class="client-info-list">
                <div>
                    <span>Personas</span>
                    <strong>0</strong>
                </div>

                <div>
                    <span>Campaigns</span>
                    <strong>0</strong>
                </div>

                <div>
                    <span>Status</span>
                    <strong>Needs Persona</strong>
                </div>
            </div>

            <div class="client-actions">
                <a href="{{ url('/agency/clients/show') }}" class="mini-btn">Continue Setup</a>
                <button class="mini-btn danger" type="button" data-deactivate-client>Deactivate</button>
            </div>

        </div>

        <div class="client-card deactivated">

            <div class="client-card-top">
                <div class="client-logo">O</div>

                <div>
                    <h3>Old Studio</h3>
                    <p>Inactive profile · Previous campaigns preserved</p>
                </div>
            </div>

            <div class="client-info-list">
                <div>
                    <span>Personas</span>
                    <strong>2</strong>
                </div>

                <div>
                    <span>Campaigns</span>
                    <strong>4</strong>
                </div>

                <div>
                    <span>Status</span>
                    <strong>Inactive</strong>
                </div>
            </div>

            <div class="client-actions">
                <button class="mini-btn success" type="button" data-reactivate-client>Reactivate</button>
            </div>

        </div>

    </div>

    <div class="capacity-card">
        <div>
            <h3>Client Capacity</h3>
            <p>Your client limit is set by the founder when your account is created.</p>
        </div>

        <div class="capacity-bar">
            <span style="width:60%;"></span>
        </div>
    </div>

</div>

@endsection