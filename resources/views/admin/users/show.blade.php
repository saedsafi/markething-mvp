@extends('layouts.dashboard')

@section('title', 'Agency User - MARKETHING')

@section('page-title', $agencyUser->name)
@section('page-subtitle', 'Manage agency account, limits, access, and generated campaigns.')

@section('user-name', auth()->user()->name ?? 'Founder Admin')
@section('user-role', 'Platform Owner')

@section('dashboard-content')

<div class="admin-detail-page">

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

    <div class="admin-user-hero">

        <div class="client-profile-main">

            <div class="client-logo large">
                {{ strtoupper(substr($agencyUser->name, 0, 1)) }}
            </div>

            <div>

                @if ($agencyUser->status === 'active')
                    <span class="hero-badge">Active Account</span>
                @elseif ($agencyUser->status === 'inactive')
                    <span class="hero-badge inactive-badge">Inactive Account</span>
                @else
                    <span class="hero-badge suspended-badge">Suspended Account</span>
                @endif

                <h2>{{ $agencyUser->name }}</h2>

                <p>
                    {{ $agencyUser->email }}
                    · Client limit: {{ $agencyUser->client_limit }}
                    · Created {{ $agencyUser->created_at->format('M Y') }}
                </p>

            </div>

        </div>

        <div class="hero-actions">

            <button
                class="btn btn-primary"
                type="button"
                data-open-modal="tempPasswordModal"
            >
                Issue New Password
            </button>

            @if ($agencyUser->status === 'suspended')

                <form method="POST" action="{{ route('admin.users.reactivate', $agencyUser) }}">
                    @csrf
                    @method('PATCH')

                    <button class="btn btn-primary" type="submit">
                        Reactivate Account
                    </button>
                </form>

            @else

                <form method="POST" action="{{ route('admin.users.suspend', $agencyUser) }}">
                    @csrf
                    @method('PATCH')

                    <button class="btn btn-danger" type="submit">
                        Suspend Account
                    </button>
                </form>

            @endif
            <button
                class="btn btn-danger"
                type="button"
                data-open-modal="deleteAgencyModal"
            >
                Delete Agency
            </button>
        </div>

    </div>

    <div class="stats-grid">

        <x-stats-card
            label="Clients"
            value="{{ $agencyUser->clients_count }}/{{ $agencyUser->client_limit }}"
            hint="Client limit set by founder"
        />

        <x-stats-card
            label="Campaigns"
            value="{{ $agencyUser->campaigns_count }}"
            hint="Generated campaigns"
        />

        <x-stats-card
            label="Account Status"
            value="{{ ucfirst($agencyUser->status) }}"
            hint="Current account state"
        />

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
                        <th>Created</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse ($agencyUser->clients as $client)

                        <tr>

                            <td>
                                <strong>{{ $client->name }}</strong>

                                @if ($client->industry)
                                    <p class="table-muted">
                                        {{ $client->industry }}
                                    </p>
                                @endif
                            </td>

                            <td>
                                @if ($client->status === 'active')
                                    <span class="status active-status">Active</span>
                                @else
                                    <span class="status inactive-status">Inactive</span>
                                @endif
                            </td>

                            <td>
                                {{ $client->personas->count() }}
                            </td>

                            <td>
                                {{ $client->campaigns->count() }}
                            </td>

                            <td>
                                {{ $client->created_at->format('M Y') }}
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="5">
                                No client profiles yet.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </x-data-table>

        <div class="admin-side-stack">

            <div class="table-card">

                <h2 class="section-title">Update Client Limit</h2>

                <form
                    method="POST"
                    action="{{ route('admin.users.update', $agencyUser) }}"
                >
                    @csrf
                    @method('PATCH')

                    <div class="form-group">
                        <label class="form-label">
                            Maximum Number of Clients
                        </label>

                        <input
                            type="number"
                            name="client_limit"
                            class="form-input"
                            min="1"
                            value="{{ $agencyUser->client_limit }}"
                            required
                        >
                    </div>

                    <button class="btn btn-primary" type="submit">
                        Save Client Limit
                    </button>

                </form>

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
                        Password reset is founder-managed
                    </div>

                    <div class="checklist-item done">
                        <span>✓</span>
                        Client limit is founder-controlled
                    </div>

                    <div class="checklist-item active">
                        <span>!</span>
                        Suspended users cannot sign in
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<x-modal
    id="tempPasswordModal"
    title="Issue Temporary Password"
    subtitle="The user will be forced to change it on next login."
>

    <form
        method="POST"
        action="{{ route('admin.users.temporary-password', $agencyUser) }}"
    >
        @csrf
        @method('PATCH')

        <div class="form-group">

            <label class="form-label">
                Temporary Password
            </label>

            <input
                type="text"
                name="temporary_password"
                class="form-input"
                placeholder="Temp12345"
                required
            >

            <p class="input-helper">
                The account will become inactive until the user changes the password.
            </p>

        </div>

        <div class="modal-actions">

            <button class="btn btn-primary" type="submit">
                Issue Password
            </button>

            <button
                class="btn btn-secondary"
                type="button"
                data-close-modal
            >
                Cancel
            </button>

        </div>

    </form>

</x-modal>
<x-modal
    id="deleteAgencyModal"
    title="Delete Agency User"
    subtitle="This action cannot be undone."
>
    <form method="POST" action="{{ route('admin.users.destroy', $agencyUser) }}">
        @csrf
        @method('DELETE')

        <div class="validation-box">
            Are you sure you want to delete
            <strong>{{ $agencyUser->name }}</strong>?
            This may remove related clients, personas, campaigns, and logs depending on your database rules.
        </div>

        <div class="modal-actions">
            <button class="btn btn-danger" type="submit">
                Yes, Delete Agency
            </button>

            <button
                class="btn btn-secondary"
                type="button"
                data-close-modal
            >
                Cancel
            </button>
        </div>
    </form>
</x-modal>
@endsection