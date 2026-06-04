@extends('layouts.dashboard')

@section('title', 'Admin Dashboard - MARKETHING')

@section('page-title', 'Admin Dashboard')
@section('page-subtitle', 'Create agency users, manage access, and monitor platform activity.')

@section('user-name', auth()->user()->name ?? 'Founder Admin')
@section('user-role', 'Platform Owner')

@section('dashboard-content')

<div class="admin-page">

    @if (session('success'))
        <div class="validation-box success-box">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->default->any())
    <div class="validation-box">
        {{ $errors->default->first() }}
    </div>
    @endif

    <div class="stats-grid">
        <x-stats-card label="Total Agencies" value="{{ $totalAgencies ?? 0 }}" hint="Founder-managed accounts" />
        <x-stats-card label="Active Accounts" value="{{ $activeAccounts ?? 0 }}" hint="Can access the platform" />
        <x-stats-card label="Inactive Accounts" value="{{ $inactiveAccounts ?? 0 }}" hint="Waiting for first login" />
    </div>

    <div class="admin-main-stack">

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
                        <th>AI Tokens</th>
                        <th>Actions</th>
                        <th>Tier</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($users as $agencyUser)
                        <tr>
                            <td>
                                <strong>{{ $agencyUser->name }}</strong>
                                <p class="table-muted">
                                    Created {{ $agencyUser->created_at->format('d M Y') }}
                                </p>
                            </td>

                            <td>{{ $agencyUser->email }}</td>

                            <td class="client-limit">
                                {{ $agencyUser->client_limit }} clients
                            </td>

                            <td>
                                @if ($agencyUser->status === 'active')
                                    <span class="status active-status">Active</span>
                                @elseif ($agencyUser->status === 'inactive')
                                    <span class="status inactive-status">Inactive</span>
                                @else
                                    <span class="status suspended-status">Suspended</span>
                                @endif
                            </td>

                            <td>{{ $agencyUser->campaigns_count }}</td>

                            <td>
                                {{ number_format($agencyUser->total_tokens_used ?? 0) }}
                            </td>

                            <td>
                                <div class="table-actions-inline">

                                    <a
                                        href="{{ route('admin.users.show', $agencyUser) }}"
                                        class="simple-mini-btn"
                                    >
                                        View
                                    </a>

                                    <span>•</span>

                                    <button
                                        class="simple-mini-btn"
                                        type="button"
                                        data-open-modal="tempPasswordModal{{ $agencyUser->id }}"
                                    >
                                        Change Password
                                    </button>

                                    <span>•</span>

                                    @if ($agencyUser->status === 'suspended')
                                        <form
                                            method="POST"
                                            action="{{ route('admin.users.reactivate', $agencyUser) }}"
                                        >
                                            @csrf
                                            @method('PATCH')

                                            <button class="simple-mini-btn green" type="submit">
                                                Reactivate
                                            </button>
                                        </form>
                                    @else
                                        <form
                                            method="POST"
                                            action="{{ route('admin.users.suspend', $agencyUser) }}"
                                        >
                                            @csrf
                                            @method('PATCH')

                                            <button class="simple-mini-btn red" type="submit">
                                                Suspend
                                            </button>
                                        </form>
                                    @endif

                                </div>
                            </td>

                            <td>
                                <span class="status active-status">
                                    {{ $agencyUser->tier }}
                                </span>
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="8">
                                No agency users yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </x-data-table>

        <div class="admin-bottom-stack">

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

@foreach ($users as $agencyUser)

    <x-modal
        id="tempPasswordModal{{ $agencyUser->id }}"
        class="fullscreen-modal"
        title="Issue Temporary Password"
        subtitle="The user will be forced to change it on next login."
    >
        <form method="POST" action="{{ route('admin.users.temporary-password', $agencyUser) }}">
            @csrf
            @method('PATCH')

            <div class="form-group">
                <label class="form-label">Temporary Password</label>

                <input
                    type="text"
                    name="temporary_password"
                    class="form-input"
                    placeholder="Temp12345"
                    required
                >
            </div>

            <div class="modal-actions">
                <button class="btn btn-primary" type="submit">
                    Force Change Password
                </button>

                <button class="btn btn-secondary" type="button" data-close-modal>
                    Cancel
                </button>
            </div>
        </form>
    </x-modal>

@endforeach

<x-modal
    id="createAgencyModal"
    title="Create Agency User"
    subtitle="Create an inactive agency account with a temporary password."
>

    <div class="modal-scroll-content">

        @if ($errors->createAgency->any())
            <div class="validation-box">
                {{ $errors->createAgency->first() }}
            </div>
        @endif
        <form method="POST" action="{{ route('admin.users.store') }}">
            @csrf

            <div class="form-group">
                <label class="form-label">Display Name</label>

                <input
                type="text"
                name="name"
                class="form-input"
                placeholder="Agency's Name"
                value="{{ old('name') }}"
                required
            >
            </div>

            <div class="form-group">
                <label class="form-label">Email Address</label>

                <input
                type="email"
                name="email"
                class="form-input"
                placeholder="agency@example.com"
                value="{{ old('email') }}"
                required
            >
            </div>

            <div class="form-group">
                <label class="form-label">Maximum Number of Clients</label>

                <input
                type="number"
                name="client_limit"
                class="form-input"
                min="1"
                value="{{ old('client_limit', 10) }}"
                required
            >

                <p class="input-helper">
                    This controls how many client profiles this agency can create.
                </p>
            </div>

            <div class="form-group">
                <label class="form-label">Temporary Password</label>

                <input
                type="text"
                name="temporary_password"
                class="form-input"
                placeholder="Temp12345"
                value="{{ old('temporary_password') }}"
                required
            >

                <p class="input-helper">
                    The user will be forced to change this password on first login.
                </p>
            </div>

            <div class="modal-actions">
                <button class="btn btn-primary" type="submit">
                    Create Inactive User
                </button>

                <button class="btn btn-secondary" type="button" data-close-modal>
                    Cancel
                </button>
            </div>

        </form>

    </div>

</x-modal>
@if ($errors->createAgency->any())
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document
                .getElementById('createAgencyModal')
                ?.classList.add('show');
        });
    </script>
@endif

@endsection