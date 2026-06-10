@extends('layouts.dashboard')

@section('title', 'Campaign Details - MARKETHING')

@section('page-title', $campaign->name)
@section('page-subtitle', 'Inspect campaign data, generated posts, and AI usage.')

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
                {{ strtoupper(substr($campaign->name, 0, 1)) }}
            </div>

            <div>
                @if ($campaign->status === 'generated')
                    <span class="hero-badge">Generated Campaign</span>
                @elseif ($campaign->status === 'generating')
                    <span class="hero-badge inactive-badge">Generating Campaign</span>
                @else
                    <span class="hero-badge suspended-badge">Failed Campaign</span>
                @endif

                <h2>{{ $campaign->name }}</h2>

                <p>
                    {{ $campaign->user?->name ?? 'Deleted Agency' }}
                    · {{ $campaign->client?->name ?? 'Deleted Client' }}
                    · Created {{ $campaign->created_at->format('d M Y') }}
                </p>
            </div>

        </div>

        <div class="hero-actions">
            <a
                href="{{ route('admin.campaigns.index') }}"
                class="btn btn-secondary"
            >
                Back to Campaigns
            </a>
        </div>

    </div>

    <div class="stats-grid">

        <x-stats-card
            label="Posts"
            value="{{ $campaign->posts->count() }}/{{ $campaign->requested_posts_count }}"
            hint="Generated posts"
        />

        <x-stats-card
            label="Total Tokens"
            value="{{ number_format($totalTokens) }}"
            hint="Input + output tokens"
        />

        <x-stats-card
            label="Input Tokens"
            value="{{ number_format($totalInputTokens) }}"
            hint="Prompt tokens"
        />

        <x-stats-card
            label="Output Tokens"
            value="{{ number_format($totalOutputTokens) }}"
            hint="Response tokens"
        />

    </div>

    <div class="admin-user-grid">

        <div class="admin-main-stack">

            <div class="table-card">

                <h2 class="section-title">Campaign Overview</h2>

                <div class="details-grid">

                    <div>
                        <span>Agency</span>
                        <strong>{{ $campaign->user?->name ?? 'Deleted Agency' }}</strong>
                    </div>

                    <div>
                        <span>Agency Email</span>
                        <strong>{{ $campaign->user?->email ?? '—' }}</strong>
                    </div>

                    <div>
                        <span>Client</span>
                        <strong>{{ $campaign->client?->name ?? 'Deleted Client' }}</strong>
                    </div>

                    <div>
                        <span>Persona</span>
                        <strong>{{ $campaign->persona?->name ?? 'Deleted Persona' }}</strong>
                    </div>

                    <div>
                        <span>Status</span>
                        <strong>{{ ucfirst($campaign->status) }}</strong>
                    </div>

                    <div>
                        <span>Date Range</span>
                        <strong>
                            {{ $campaign->start_date?->format('d M Y') }}
                            →
                            {{ $campaign->end_date?->format('d M Y') }}
                        </strong>
                    </div>

                    <div>
                        <span>Channels</span>
                        <strong>{{ implode(', ', $campaign->channels ?? []) }}</strong>
                    </div>

                    <div>
                        <span>Prompt Version ID</span>
                        <strong>{{ $campaign->prompt_version_id ?? '—' }}</strong>
                    </div>

                </div>

                <div class="profile-side-divider"></div>

                <div class="form-group">
                    <label class="form-label">Objective</label>

                    <div class="readonly-content">
                        {{ $campaign->objective }}
                    </div>
                </div>

                @if ($campaign->description)
                    <div class="form-group">
                        <label class="form-label">Description</label>

                        <div class="readonly-content">
                            {{ $campaign->description }}
                        </div>
                    </div>
                @endif

            </div>

            <x-data-table title="Generated Posts">

                <table class="dashboard-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Channel</th>
                            <th>Scheduled</th>
                            <th>Caption</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($campaign->posts as $post)
                            <tr>
                                <td>{{ $post->sequence_number }}</td>

                                <td>{{ ucfirst($post->channel) }}</td>

                                <td>
                                    {{ $post->scheduled_date?->format('d M Y') ?? '—' }}
                                </td>

                                <td>
                                    <strong>{{ Str::limit($post->summary ?? $post->caption, 80) }}</strong>

                                    @if ($post->hashtags)
                                        <p class="table-muted">
                                            {{ Str::limit($post->hashtags, 80) }}
                                        </p>
                                    @endif
                                </td>

                                <td>
                                    @if ($post->is_regenerated)
                                        <span class="status inactive-status">Regenerated</span>
                                    @elseif ($post->is_edited)
                                        <span class="status inactive-status">Edited</span>
                                    @else
                                        <span class="status active-status">Original</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">
                                    No posts found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

            </x-data-table>

            <x-data-table title="LLM Activity">

                <table class="dashboard-table">
                    <thead>
                        <tr>
                            <th>Call Type</th>
                            <th>Status</th>
                            <th>Model</th>
                            <th>Tokens</th>
                            <th>Latency</th>
                            <th>Created</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($generationLogs as $log)
                            <tr>
                                <td>{{ str_replace('_', ' ', ucfirst($log->call_type)) }}</td>

                                <td>
                                    @if ($log->status === 'success')
                                        <span class="status active-status">Success</span>
                                    @else
                                        <span class="status suspended-status">Failed</span>
                                    @endif
                                </td>

                                <td>{{ $log->model ?? '—' }}</td>

                                <td>
                                    {{ number_format(($log->input_tokens ?? 0) + ($log->output_tokens ?? 0)) }}
                                    <p class="table-muted">
                                        In: {{ number_format($log->input_tokens ?? 0) }}
                                        · Out: {{ number_format($log->output_tokens ?? 0) }}
                                    </p>
                                </td>

                                <td>
                                    {{ $log->latency_ms ? number_format($log->latency_ms) . ' ms' : '—' }}
                                </td>

                                <td>{{ $log->created_at->format('d M Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    No LLM logs found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

            </x-data-table>

        </div>

        <div class="admin-side-stack">

            <div class="table-card">

                <h2 class="section-title">Snapshot Protection</h2>

                <div class="checklist">
                    <div class="checklist-item done">
                        <span>✓</span>
                        Client snapshot saved at generation time
                    </div>

                    <div class="checklist-item done">
                        <span>✓</span>
                        Persona snapshot saved at generation time
                    </div>

                    <div class="checklist-item done">
                        <span>✓</span>
                        Regeneration uses campaign snapshot
                    </div>
                </div>

            </div>

            <div class="table-card">

                <h2 class="section-title">Snapshot Summary</h2>

                <div class="details-list">

                    <div>
                        <span>Snapshot Client</span>
                        <strong>
                            {{ data_get($campaign->snapshot, 'client.name', '—') }}
                        </strong>
                    </div>

                    <div>
                        <span>Snapshot Industry</span>
                        <strong>
                            {{ data_get($campaign->snapshot, 'client.industry', '—') }}
                        </strong>
                    </div>

                    <div>
                        <span>Snapshot Persona</span>
                        <strong>
                            {{ data_get($campaign->snapshot, 'persona.name', '—') }}
                        </strong>
                    </div>

                    <div>
                        <span>Requested Posts</span>
                        <strong>
                            {{ data_get($campaign->snapshot, 'campaign.requested_posts_count', $campaign->requested_posts_count) }}
                        </strong>
                    </div>

                </div>

            </div>

            <div class="table-card danger-card">

                <h2 class="section-title">Danger Zone</h2>

                <p class="section-description">
                    Delete this campaign from the admin panel. This cannot be undone.
                </p>

                <button
                    class="btn btn-danger full-btn"
                    type="button"
                    data-open-modal="deleteCampaignModal"
                >
                    Delete Campaign
                </button>

            </div>

        </div>

    </div>

</div>

<x-modal
    id="deleteCampaignModal"
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