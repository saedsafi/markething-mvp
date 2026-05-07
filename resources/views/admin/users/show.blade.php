@extends('layouts.dashboard')

@section('title', 'User Detail - MARKETHING')

@section('page-title', 'Nova Marketing')
@section('page-subtitle', 'Manage agency account, usage, clients, campaigns, and access.')

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
                <p>nova@example.com · Basic tier · Created May 2026</p>
            </div>
        </div>

        <div class="hero-actions">
            <button class="btn btn-secondary" type="button" data-reset-temp-password>
                Reset Temp Password
            </button>

            <button class="btn btn-danger" type="button" data-suspend-user>
                Suspend Account
            </button>
        </div>

    </div>

    <div class="stats-grid">

        <x-stats-card label="Clients" value="6/10" hint="Plan limit" />
        <x-stats-card label="Campaigns" value="24" hint="All time" />
        <x-stats-card label="Token Usage" value="42K" hint="This month" />

    </div>

    <div class="admin-user-grid">

        <x-data-table title="Client Profiles">

            <table class="dashboard-table">
                <thead>
                    <tr>
                        <th>Client</th>
                        <th>Personas</th>
                        <th>Campaigns</th>
                        <th>Last Updated</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td>Bloom Café</td>
                        <td>3</td>
                        <td>8</td>
                        <td>May 2026</td>
                    </tr>

                    <tr>
                        <td>Luna Boutique</td>
                        <td>2</td>
                        <td>5</td>
                        <td>April 2026</td>
                    </tr>

                    <tr>
                        <td>Nova Fitness</td>
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
                        Issue Temp Password
                    </button>

                    <button class="shortcut-card" type="button">
                        <span>↕</span>
                        Change Tier
                    </button>

                    <button class="shortcut-card danger-shortcut" type="button">
                        <span>!</span>
                        Delete Account
                    </button>
                </div>
            </div>

            <div class="table-card">
                <h2 class="section-title">Usage Breakdown</h2>

                <div class="usage-list">
                    <div class="usage-row">
                        <span>Campaign Generation</span>
                        <strong>28K tokens</strong>
                    </div>

                    <div class="usage-row">
                        <span>Post Regeneration</span>
                        <strong>6K tokens</strong>
                    </div>

                    <div class="usage-row">
                        <span>AI Assist</span>
                        <strong>8K tokens</strong>
                    </div>

                    <div class="usage-row total">
                        <span>Estimated Cost</span>
                        <strong>$12.80</strong>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>

@endsection