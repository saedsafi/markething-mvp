@extends('layouts.dashboard')

@section('title', 'Agency Dashboard - MARKETHING')

@section('page-title', 'Agency Dashboard')
@section('page-subtitle', 'Manage clients, campaigns, and AI-generated marketing content.')

@section('user-name', 'Nova Marketing')
@section('user-role', 'Agency Account')

@section('dashboard-content')

<div class="agency-page">

    <div class="agency-hero-card">

        <div>
            <span class="hero-badge">AI Marketing Studio</span>

            <h2>Ready to build your next campaign?</h2>

            <p>
                Create a client profile, define personas, and generate structured campaign posts in minutes.
            </p>
        </div>

        <div class="hero-actions">
            <x-button variant="btn-primary" type="button" data-create-campaign>
                + Create New Campaign
            </x-button>

            <x-button variant="btn-secondary" type="button" data-create-client>
                Create Client Profile
            </x-button>
        </div>

    </div>

    <div class="stats-grid">

        <x-stats-card
            label="Active Campaigns"
            value="12"
            hint="3 generated this month"
        />

        <x-stats-card
            label="Client Profiles"
            value="6/10"
            hint="4 profiles remaining"
        />

        <x-stats-card
            label="AI Assists Today"
            value="18/50"
            hint="Daily limit resets at midnight"
        />

    </div>

    <div class="agency-grid">

        <x-data-table title="Recent Campaigns">

            <x-slot name="action">
                <x-button variant="btn-primary" type="button">
                    + Create Campaign
                </x-button>
            </x-slot>

            <table class="dashboard-table">
                <thead>
                    <tr>
                        <th>Campaign</th>
                        <th>Client</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Posts</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td>
                            <strong>Summer Launch</strong>
                            <p class="table-muted">Instagram + Facebook</p>
                        </td>
                        <td>Bloom Café</td>
                        <td>
                            <span class="status active-status">Generated</span>
                        </td>
                        <td>May 2026</td>
                        <td>14</td>
                    </tr>

                    <tr>
                        <td>
                            <strong>Ramadan Offers</strong>
                            <p class="table-muted">Instagram</p>
                        </td>
                        <td>Luna Boutique</td>
                        <td>
                            <span class="status pending-status">Needs Review</span>
                        </td>
                        <td>April 2026</td>
                        <td>9</td>
                    </tr>

                    <tr>
                        <td>
                            <strong>Brand Awareness</strong>
                            <p class="table-muted">Facebook</p>
                        </td>
                        <td>Nova Fitness</td>
                        <td>
                            <span class="status active-status">Generated</span>
                        </td>
                        <td>April 2026</td>
                        <td>18</td>
                    </tr>
                </tbody>
            </table>

        </x-data-table>

        <div class="agency-side-stack">

            <div class="table-card">
                <h2 class="section-title">Workflow Progress</h2>

                <div class="workflow-list">

                    <div class="workflow-item done">
                        <span>1</span>
                        <div>
                            <strong>Create client profile</strong>
                            <p>Business and brand information saved.</p>
                        </div>
                    </div>

                    <div class="workflow-item done">
                        <span>2</span>
                        <div>
                            <strong>Add audience persona</strong>
                            <p>At least one persona is required.</p>
                        </div>
                    </div>

                    <div class="workflow-item active">
                        <span>3</span>
                        <div>
                            <strong>Create campaign</strong>
                            <p>Select client, persona, channels, and dates.</p>
                        </div>
                    </div>

                    <div class="workflow-item">
                        <span>4</span>
                        <div>
                            <strong>Review generated posts</strong>
                            <p>Edit, copy, save, or regenerate post content.</p>
                        </div>
                    </div>

                </div>
            </div>

            <div class="table-card">
                <h2 class="section-title">Recent AI Activity</h2>

                <div class="activity-list">

                    <div class="activity-item">
                        <div class="activity-icon">✦</div>

                        <div>
                            <strong>AI assist used</strong>
                            <p>Brand voice answer drafted for Bloom Café.</p>
                            <small>Today</small>
                        </div>
                    </div>

                    <div class="activity-item">
                        <div class="activity-icon">◎</div>

                        <div>
                            <strong>Campaign generated</strong>
                            <p>Summer Launch campaign created with 14 posts.</p>
                            <small>Yesterday</small>
                        </div>
                    </div>

                    <div class="activity-item">
                        <div class="activity-icon">↻</div>

                        <div>
                            <strong>Post regenerated</strong>
                            <p>Alternative caption generated for Instagram post.</p>
                            <small>2 days ago</small>
                        </div>
                    </div>

                </div>
            </div>

        </div>

    </div>

    <div class="dashboard-bottom-grid">

        <x-empty-state
            title="No urgent notifications"
            description="Important campaign alerts, account updates, and AI usage notices will appear here."
        >
            <x-slot name="action">
                <x-button variant="btn-secondary" type="button">
                    View All Notifications
                </x-button>
            </x-slot>
        </x-empty-state>

        <div class="table-card">
            <h2 class="section-title">Usage Limits</h2>

            <div class="limit-list">

                <div class="limit-row">
                    <div>
                        <strong>Client Profiles</strong>
                        <p>6 of 10 used</p>
                    </div>
                    <div class="limit-bar">
                        <span style="width:60%;"></span>
                    </div>
                </div>

                <div class="limit-row">
                    <div>
                        <strong>Personas Per Client</strong>
                        <p>Up to 5 personas</p>
                    </div>
                    <div class="limit-bar">
                        <span style="width:40%;"></span>
                    </div>
                </div>

                <div class="limit-row">
                    <div>
                        <strong>AI Assists Today</strong>
                        <p>18 of 50 used</p>
                    </div>
                    <div class="limit-bar">
                        <span style="width:36%;"></span>
                    </div>
                </div>

            </div>

        </div>

    </div>

</div>

@endsection