@extends('layouts.dashboard')

@section('title', 'Campaign Management - MARKETHING')

@section('page-title', 'Campaign Management')
@section('page-subtitle', 'Inspect, monitor, and delete campaigns across all agencies.')

@section('user-name', auth()->user()->name ?? 'Founder Admin')
@section('user-role', 'Platform Owner')

@section('dashboard-content')

<div class="admin-page">

    @if (session('success'))
        <div class="validation-box success-box">
            {{ session('success') }}
        </div>
    @endif

    <div class="stats-grid">
        <x-stats-card label="Total Campaigns" value="{{ $totalCampaigns }}" hint="All agency campaigns" />
        <x-stats-card label="Generated" value="{{ $generatedCampaigns }}" hint="Completed successfully" />
        <x-stats-card label="Generating" value="{{ $generatingCampaigns }}" hint="Currently in progress" />
        <x-stats-card label="Failed" value="{{ $failedCampaigns }}" hint="Generation failed" />
    </div>

    <x-data-table title="All Campaigns">

        <form
            method="GET"
            action="{{ route('admin.campaigns.index') }}"
            class="agency-filters"
            id="adminCampaignFiltersForm"
        >
            <input
                id="adminCampaignSearch"
                type="text"
                name="search"
                class="form-input"
                placeholder="Search campaign, agency, client..."
                value="{{ request('search') }}"
            >

            <select name="status" class="form-select">
                <option value="">All Statuses</option>
                <option value="generated" @selected(request('status') === 'generated')>
                    Generated
                </option>
                <option value="generating" @selected(request('status') === 'generating')>
                    Generating
                </option>
                <option value="failed" @selected(request('status') === 'failed')>
                    Failed
                </option>
            </select>

            <select name="sort" class="form-select">
                <option value="">Newest First</option>
                <option value="oldest" @selected(request('sort') === 'oldest')>
                    Oldest First
                </option>
                <option value="posts_desc" @selected(request('sort') === 'posts_desc')>
                    Most Posts
                </option>
                <option value="posts_asc" @selected(request('sort') === 'posts_asc')>
                    Least Posts
                </option>
                <option value="name_asc" @selected(request('sort') === 'name_asc')>
                    Name A-Z
                </option>
                <option value="name_desc" @selected(request('sort') === 'name_desc')>
                    Name Z-A
                </option>
            </select>
        </form>

        <table class="dashboard-table">
            <thead>
                <tr>
                    <th>Campaign</th>
                    <th>Agency</th>
                    <th>Client</th>
                    <th>Status</th>
                    <th>Posts</th>
                    <th>Date Range</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($campaigns as $campaign)
                    <tr>
                        <td>
                            <strong>{{ $campaign->name }}</strong>
                            <p class="table-muted">
                                {{ $campaign->objective }}
                            </p>
                        </td>

                        <td>
                            {{ $campaign->user?->name ?? 'Deleted Agency' }}
                            <p class="table-muted">
                                {{ $campaign->user?->email }}
                            </p>
                        </td>

                        <td>
                            {{ $campaign->client?->name ?? 'Deleted Client' }}
                            <p class="table-muted">
                                {{ $campaign->client?->industry }}
                            </p>
                        </td>

                        <td>
                            @if ($campaign->status === 'generated')
                                <span class="status active-status">Generated</span>
                            @elseif ($campaign->status === 'generating')
                                <span class="status inactive-status">Generating</span>
                            @else
                                <span class="status suspended-status">Failed</span>
                            @endif
                        </td>

                        <td>{{ $campaign->posts_count }}</td>

                        <td>
                            {{ $campaign->start_date?->format('d M Y') }}
                            →
                            {{ $campaign->end_date?->format('d M Y') }}
                        </td>

                        <td>
                            {{ $campaign->created_at->format('d M Y') }}
                        </td>

                        <td>
                            <div class="table-actions-inline">
                                <a
                                    href="{{ route('admin.campaigns.show', $campaign) }}"
                                    class="simple-mini-btn"
                                >
                                    View
                                </a>

                                <span>•</span>

                                <button
                                    class="simple-mini-btn red"
                                    type="button"
                                    data-open-modal="deleteCampaignModal{{ $campaign->id }}"
                                >
                                    Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8">
                            No campaigns found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="pagination-wrapper">
            {{ $campaigns->links() }}
        </div>

    </x-data-table>

</div>

@foreach ($campaigns as $campaign)
    <x-modal
        id="deleteCampaignModal{{ $campaign->id }}"
        title="Delete Campaign"
        subtitle="This action cannot be undone."
    >
        <form method="POST" action="{{ route('admin.campaigns.destroy', $campaign) }}">
            @csrf
            @method('DELETE')

            <div class="validation-box">
                Are you sure you want to delete
                <strong>{{ $campaign->name }}</strong>?
                Generated posts and related campaign data may also be removed depending on your database rules.
            </div>

            <div class="modal-actions">
                <button class="btn btn-danger" type="submit">
                    Yes, Delete Campaign
                </button>

                <button class="btn btn-secondary" type="button" data-close-modal>
                    Cancel
                </button>
            </div>
        </form>
    </x-modal>
@endforeach

<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('adminCampaignFiltersForm');
    const search = document.getElementById('adminCampaignSearch');

    if (!form) {
        return;
    }

    let searchTimeout;

    search?.addEventListener('input', () => {
        clearTimeout(searchTimeout);

        searchTimeout = setTimeout(() => {
            form.submit();
        }, 450);
    });

    form
        .querySelectorAll('select')
        .forEach((select) => {
            select.addEventListener('change', () => {
                form.submit();
            });
        });
});
</script>

@endsection