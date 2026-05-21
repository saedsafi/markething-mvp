@extends('layouts.dashboard')

@section('title', 'Campaign Output - MARKETHING')

@section('page-title', 'Campaign Output')
@section('page-subtitle', 'View all generated campaigns and open individual campaign outputs.')

@section('user-name', auth()->user()->name ?? 'Agency User')
@section('user-role', 'Agency Account')

@section('dashboard-content')

<div class="campaign-output-page">

    @if (session('success'))
        <div class="validation-box success-box">
            {{ session('success') }}
        </div>
    @endif

    <div class="campaign-output-hero">

        <div>
            <span class="hero-badge">
                Campaigns
            </span>

            <h2>
                Campaign Output
            </h2>

            <p>
                Review all generated campaigns, open campaign outputs, and continue creating new campaign content.
            </p>
        </div>

        <div class="hero-actions">
            <a href="{{ route('agency.campaigns.create') }}" class="btn btn-primary">
                + Create Campaign
            </a>
        </div>

    </div>

    <div class="table-card">

        <div class="table-header">
            <div>
                <h2 class="section-title">
                    All Campaigns
                </h2>

                <p class="section-description">
                    Showing campaigns created by your agency account.
                </p>
            </div>
        </div>

        @if ($campaigns->count())

            <div class="table-responsive">

                <table class="dashboard-table">

                    <thead>
                        <tr>
                            <th>Campaign</th>
                            <th>Client</th>
                            <th>Persona</th>
                            <th>Posts</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach ($campaigns as $campaign)

                            <tr>
                                <td>
                                    <strong>
                                        {{ $campaign->name }}
                                    </strong>

                                    <p class="table-muted">
                                        {{ \Illuminate\Support\Str::limit($campaign->objective ?? 'No objective set', 80) }}
                                    </p>
                                </td>

                                <td>
                                    {{ $campaign->client?->name ?? 'Deleted / Missing Client' }}
                                </td>

                                <td>
                                    {{ $campaign->persona?->name ?? 'No Persona' }}
                                </td>

                                <td>
                                    {{ $campaign->posts_count }}
                                </td>

                                <td>
                                    @if ($campaign->status === 'generated')
                                        <span class="status active-status">
                                            Generated
                                        </span>
                                    @elseif ($campaign->status === 'failed')
                                        <span class="status suspended-status">
                                            Failed
                                        </span>
                                    @else
                                        <span class="status inactive-status">
                                            {{ ucfirst($campaign->status ?? 'Draft') }}
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    {{ $campaign->created_at->format('M d, Y') }}
                                </td>

                                <td>
                                    <a
                                        href="{{ route('agency.campaigns.show', $campaign) }}"
                                        class="mini-btn"
                                    >
                                        View Output
                                    </a>
                                </td>
                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

            <div class="pagination-wrapper">
                {{ $campaigns->links() }}
            </div>

        @else

            <x-empty-state
                title="No campaigns yet"
                description="Create your first campaign to generate campaign output."
            >
                <a href="{{ route('agency.campaigns.create') }}" class="btn btn-primary">
                    + Create Campaign
                </a>
            </x-empty-state>

        @endif

    </div>

</div>

@endsection