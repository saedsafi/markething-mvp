@extends('layouts.dashboard')

@section('title', 'User Detail - MARKETHING')

@section('page-title', 'Nova Marketing')
@section('page-subtitle', 'Manage agency account, client limit, clients, campaigns, and access.')

@section('user-name', 'Founder Admin')
@section('user-role', 'Platform Owner')

@section('dashboard-content')

<div class="admin-detail-page">

    <div class="admin-user-hero">

        <div class="client-profile-main">
            <div class="client-logo large">N</div>

            <div>
                <span class="hero-badge">Active Account</span>
                <h2>Nova Marketing</h2>
                <p>nova@example.com · Client limit: 10 · Created May 2026</p>
            </div>
        </div>

        <div class="hero-actions">
            <button class="btn btn-secondary" type="button" data-reset-temp-password>
                Issue New Password
            </button>

            <button class="btn btn-danger" type="button" data-suspend-user>
                Suspend Account
            </button>
        </div>

    </div>

    <div class="stats-grid">

        <x-stats-card label="Clients" value="6/10" hint="Client limit set by founder" />
        <x-stats-card label="Campaigns" value="24" hint="Generated campaigns" />
        <x-stats-card label="Account Status" value="Active" hint="Can access platform" />

    </div>

    <div class="admin-user-grid">

        <x-data-table title="Client Profiles">

            <table class="dashboard-table">
                <thead>
                    <tr>
                        <th>Client</th>
                        <th>Status</th>
                        <th>Personas</th>
                        <th>Campaigns</th>
                        <th>Last Updated</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td>Bloom Café</td>
                        <td><span class="status active-status">Active</span></td>
                        <td>3</td>
                        <td>8</td>
                        <td>May 2026</td>
                    </tr>

                    <tr>
                        <td>Luna Boutique</td>
                        <td><span class="status active-status">Active</span></td>
                        <td>2</td>
                        <td>5</td>
                        <td>April 2026</td>
                    </tr>

                    <tr>
                        <td>Nova Fitness</td>
                        <td><span class="status inactive-status">Needs Persona</span></td>
                        <td>0</td>
                        <td>0</td>
                        <td>April 2026</td>
                    </tr>
                </tbody>
            </table>

        </x-data-table>

        <div class="admin-side-stack">

            <div class="table-card">
                <h2 class="section-title">Account Controls</h2>

                <div class="shortcut-list">
                    <button class="shortcut-card" type="button" data-reset-temp-password>
                        <span>🔑</span>
                        Issue New Password
                    </button>

                    <button class="shortcut-card" type="button">
                        <span>#</span>
                        Update Client Limit
                    </button>

                    <button class="shortcut-card danger-shortcut" type="button" data-suspend-user>
                        <span>!</span>
                        Suspend Account
                    </button>
                </div>
            </div>

            <div class="table-card">
                <h2 class="section-title">Account Rules</h2>

                <div class="checklist">
                    <div class="checklist-item done">
                        <span>✓</span>
                        No self-service signup
                    </div>

                    <div class="checklist-item done">
                        <span>✓</span>
                        Password reset is admin-handled
                    </div>

                    <div class="checklist-item done">
                        <span>✓</span>
                        Client limit is set during user creation
                    </div>

                    <div class="checklist-item active">
                        <span>!</span>
                        Suspended accounts cannot sign in
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>

@endsection