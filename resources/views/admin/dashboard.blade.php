@extends('layouts.dashboard')

@section('title', 'Admin Dashboard - MARKETHING')

@section('page-title', 'Admin Dashboard')
@section('page-subtitle', 'Create agencies, manage users, and monitor platform usage.')

@section('user-name', 'Founder Admin')
@section('user-role', 'Platform Owner')

@section('dashboard-content')

<div class="admin-page">

    <div class="stats-grid">

        <x-stats-card
            label="Total Agencies"
            value="42"
            hint="+6 this month"
        />

        <x-stats-card
            label="Active Accounts"
            value="38"
            hint="4 pending first login"
        />

        <x-stats-card
            label="AI Usage"
            value="128K"
            hint="Tokens this month"
        />

    </div>

    <div class="admin-grid">

        <x-data-table title="Marketing Agencies">

            <x-slot name="action">
                <x-button
                    type="button"
                    variant="btn-primary"
                    data-open-modal="createAgencyModal"
                >
                    + Create Agency
                </x-button>
            </x-slot>

            <table class="dashboard-table">
                <thead>
                    <tr>
                        <th>Agency</th>
                        <th>Email</th>
                        <th>Tier</th>
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
                        <td>Basic</td>
                        <td>
                            <span class="status active-status">Active</span>
                        </td>
                        <td>24</td>
                        <td>
                            <div class="table-actions">
                                <button class="mini-btn">View</button>
                                <button class="mini-btn">Reset Password</button>
                                <button class="mini-btn danger">Suspend</button>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <strong>BluePeak Agency</strong>
                            <p class="table-muted">Created May 2026</p>
                        </td>
                        <td>bluepeak@example.com</td>
                        <td>Basic</td>
                        <td>
                            <span class="status pending-status">First Login Pending</span>
                        </td>
                        <td>0</td>
                        <td>
                            <div class="table-actions">
                                <button class="mini-btn">View</button>
                                <button class="mini-btn">Reset Password</button>
                                <button class="mini-btn danger">Suspend</button>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <strong>Pixel House</strong>
                            <p class="table-muted">Created April 2026</p>
                        </td>
                        <td>pixel@example.com</td>
                        <td>Pro</td>
                        <td>
                            <span class="status suspended-status">Suspended</span>
                        </td>
                        <td>13</td>
                        <td>
                            <div class="table-actions">
                                <button class="mini-btn">View</button>
                                <button class="mini-btn success">Reactivate</button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>

        </x-data-table>

        <div class="admin-side-stack">

            <div class="table-card">
                <h2 class="section-title">Usage Summary</h2>

                <div class="usage-list">
                    <div class="usage-row">
                        <span>Campaign Generation</span>
                        <strong>82K tokens</strong>
                    </div>

                    <div class="usage-row">
                        <span>Post Regeneration</span>
                        <strong>18K tokens</strong>
                    </div>

                    <div class="usage-row">
                        <span>AI Assist Calls</span>
                        <strong>28K tokens</strong>
                    </div>

                    <div class="usage-row total">
                        <span>Estimated Cost</span>
                        <strong>$37.40</strong>
                    </div>
                </div>
            </div>

            <div class="table-card">
                <h2 class="section-title">Admin Shortcuts</h2>

                <div class="shortcut-list">
                    <button class="shortcut-card">
                        <span>✦</span>
                        Prompt Editor
                    </button>

                    <button class="shortcut-card">
                        <span>⚙</span>
                        Configuration
                    </button>

                    <button class="shortcut-card">
                        <span>◷</span>
                        LLM Logs
                    </button>
                </div>
            </div>

        </div>

    </div>

</div>

<x-modal
    id="createAgencyModal"
    title="Create Marketing Agency"
    subtitle="Create a new agency account with a temporary password."
>

    <form>

        <div class="form-group">
            <label class="form-label">Agency Display Name</label>
            <input
                type="text"
                class="form-input"
                placeholder="Nova Marketing"
            >
        </div>

        <div class="form-group">
            <label class="form-label">Agency Email</label>
            <input
                type="email"
                class="form-input"
                placeholder="agency@example.com"
            >
        </div>

        <div class="form-group">
            <label class="form-label">Tier</label>

            <select class="form-input">
                <option>Basic</option>
                <option>Pro</option>
                <option>Enterprise</option>
            </select>
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
                User will be forced to change this password on first login.
            </p>
        </div>

        <div class="modal-actions">
            <x-button variant="btn-primary" type="button">
                Create Agency
            </x-button>

            <x-button variant="btn-secondary" type="button" data-close-modal>
                Cancel
            </x-button>
        </div>

    </form>

</x-modal>

<x-toast
    id="appToast"
    title="Agency Created"
    message="The agency account was created successfully."
/>

@endsection