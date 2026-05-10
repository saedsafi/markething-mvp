@extends('layouts.dashboard')

@section('title', 'Admin Dashboard - MARKETHING')

@section('page-title', 'Admin Dashboard')
@section('page-subtitle', 'Create agency users, manage access, and monitor platform activity.')

@section('user-name', 'Founder Admin')
@section('user-role', 'Platform Owner')

@section('dashboard-content')

<div class="admin-page">

    <div class="stats-grid">

        <x-stats-card
            label="Total Agencies"
            value="42"
            hint="Founder-managed accounts"
        />

        <x-stats-card
            label="Active Accounts"
            value="38"
            hint="Can access the platform"
        />

        <x-stats-card
            label="Inactive Accounts"
            value="4"
            hint="Waiting for first login"
        />

    </div>

    <div class="admin-grid">

        <x-data-table title="Agency Users">

            <x-slot name="action">
                <x-button
                    type="button"
                    variant="btn-primary"
                    data-open-modal="createAgencyModal"
                >
                    + Create Agency User
                </x-button>
            </x-slot>

            <table class="dashboard-table">
                <thead>
                    <tr>
                        <th>Agency</th>
                        <th>Email</th>
                        <th>Client Limit</th>
                        <th>Status</th>
                        <th>Campaigns</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>

                    <tr>
                        <td>
                            <strong>Nova Marketing</strong>
                            <p class="table-muted">Created May 2026</p>
                        </td>

                        <td>nova@example.com</td>

                        <td>10 clients</td>

                        <td>
                            <span class="status active-status">Active</span>
                        </td>

                        <td>24</td>

                        <td>
                            <div class="table-actions">
                                <a href="{{ url('/admin/users/show') }}" class="mini-btn">View</a>
                                <button class="mini-btn" type="button" data-reset-temp-password>
                                    Issue Password
                                </button>
                                <button class="mini-btn danger" type="button" data-suspend-user>
                                    Suspend
                                </button>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <strong>BluePeak Agency</strong>
                            <p class="table-muted">Created May 2026</p>
                        </td>

                        <td>bluepeak@example.com</td>

                        <td>8 clients</td>

                        <td>
                            <span class="status inactive-status">Inactive</span>
                        </td>

                        <td>0</td>

                        <td>
                            <div class="table-actions">
                                <a href="{{ url('/admin/users/show') }}" class="mini-btn">View</a>
                                <button class="mini-btn" type="button" data-reset-temp-password>
                                    Issue Password
                                </button>
                                <button class="mini-btn danger" type="button" data-suspend-user>
                                    Suspend
                                </button>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <strong>Pixel House</strong>
                            <p class="table-muted">Created April 2026</p>
                        </td>

                        <td>pixel@example.com</td>

                        <td>5 clients</td>

                        <td>
                            <span class="status suspended-status">Suspended</span>
                        </td>

                        <td>13</td>

                        <td>
                            <div class="table-actions">
                                <a href="{{ url('/admin/users/show') }}" class="mini-btn">View</a>
                                <button class="mini-btn success" type="button" data-reactivate-user>
                                    Reactivate
                                </button>
                            </div>
                        </td>
                    </tr>

                </tbody>
            </table>

        </x-data-table>

        <div class="admin-side-stack">

            <div class="table-card">
                <h2 class="section-title">Admin Shortcuts</h2>

                <div class="shortcut-list">

                    <a href="{{ url('/admin/prompts') }}" class="shortcut-card">
                        <span>✦</span>
                        Prompt Editor
                    </a>

                    <a href="{{ url('/admin/settings') }}" class="shortcut-card">
                        <span>⚙</span>
                        Configuration
                    </a>

                    <a href="{{ url('/admin/logs') }}" class="shortcut-card">
                        <span>◷</span>
                        LLM Logs
                    </a>

                </div>
            </div>

            <div class="table-card">
                <h2 class="section-title">Account Rules</h2>

                <div class="checklist">
                    <div class="checklist-item done">
                        <span>✓</span>
                        No public signup
                    </div>

                    <div class="checklist-item done">
                        <span>✓</span>
                        Founder creates users manually
                    </div>

                    <div class="checklist-item active">
                        <span>!</span>
                        New users start inactive until first login
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>

<x-modal
    id="createAgencyModal"
    title="Create Agency User"
    subtitle="Create an inactive agency account with a temporary password."
>

    <form>

        <div class="form-group">
            <label class="form-label">Display Name</label>
            <input
                type="text"
                class="form-input"
                placeholder="Nova Marketing"
            >
        </div>

        <div class="form-group">
            <label class="form-label">Email Address</label>
            <input
                type="email"
                class="form-input"
                placeholder="agency@example.com"
            >
        </div>

        <div class="form-group">
            <label class="form-label">Maximum Number of Clients</label>
            <input
                type="number"
                class="form-input"
                min="1"
                placeholder="10"
            >

            <p class="input-helper">
                This controls how many client profiles this agency can create.
            </p>
        </div>

        <div class="form-group">
            <label class="form-label">Temporary Password</label>

            <div class="password-generate-row">
                <input
                    type="text"
                    class="form-input"
                    placeholder="Temporary password"
                >

                <button class="secondary-btn generate-btn" type="button">
                    Generate
                </button>
            </div>

            <p class="input-helper">
                The user will be forced to change this password on first login. The account becomes active after that.
            </p>
        </div>

        <div class="modal-actions">
            <x-button variant="btn-primary" type="button" data-create-agency-submit>
                Create Inactive User
            </x-button>

            <x-button variant="btn-secondary" type="button" data-close-modal>
                Cancel
            </x-button>
        </div>

    </form>

</x-modal>

<x-toast
    id="appToast"
    title="Action Completed"
    message="The admin action was completed successfully."
/>

@endsection