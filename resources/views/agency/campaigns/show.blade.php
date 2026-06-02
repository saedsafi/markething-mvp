@extends('layouts.dashboard')

@section('title', $campaign->name . ' - MARKETHING')

@section('page-title', $campaign->name)

@section(
    'page-subtitle',
    'Review generated campaign posts, captions, and creative directions.'
)

@section('user-name', auth()->user()->name ?? 'Agency User')
@section('user-role', 'Agency Account')

@section('dashboard-content')

<div class="campaign-output-page">

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

    <div class="campaign-output-hero">

        <div>

            @if ($campaign->status === 'generated')

                <span class="hero-badge">
                    Campaign Generated
                </span>

            @elseif ($campaign->status === 'failed')

                <span class="hero-badge suspended-badge">
                    Generation Failed
                </span>

            @else

                <span class="hero-badge inactive-badge">
                    Generating
                </span>

            @endif

            <h2>{{ $campaign->name }}</h2>

            <p>
                {{ $campaign->objective }}
                · {{ $campaign->posts->count() }} generated posts
                · {{ $campaign->start_date->format('M d') }}
                —
                {{ $campaign->end_date->format('M d, Y') }}
            </p>

        </div>

        <div class="hero-actions">

            <a
                href="{{ route('agency.campaigns.create') }}"
                class="btn btn-secondary"
            >
                + New Campaign
            </a>

            <button
                class="btn btn-primary"
                type="button"
                onclick="window.print()"
            >
                Export / Print
            </button>

        </div>

    </div>

    <div class="stats-grid">

        <x-stats-card
            label="Posts"
            value="{{ $campaign->posts->count() }}"
            hint="Generated outputs"
        />

        <x-stats-card
            label="Channels"
            value="{{ count($campaign->channels) }}"
            hint="Selected platforms"
        />

        <x-stats-card
            label="Client"
            value="{{ $campaign->client?->name }}"
            hint="Business profile"
        />

        <x-stats-card
            label="Persona"
            value="{{ $campaign->persona?->name }}"
            hint="Audience target"
        />

    </div>

    <div class="campaign-output-grid">

        <div class="campaign-posts-column">

            @forelse ($campaign->posts as $post)

                <div class="campaign-post-card">

                    <div class="campaign-post-top">

                        <div>

                            <span class="post-number">
                                Post #{{ $post->sequence_number }}
                            </span>

                            <h3>
                                {{ $post->summary }}
                            </h3>

                            <p>
                                {{ ucfirst($post->channel) }}
                                ·
                                {{ ucfirst($post->media_type) }}
                                ·
                                {{ $post->scheduled_date?->format('M d, Y') }}
                            </p>

                        </div>

                        <div class="campaign-post-actions">

                            @if ($post->is_edited)

                                <span class="edited-pill">
                                    Edited
                                </span>

                            @endif

                            @if ($post->is_regenerated)

                                <span class="edited-pill regenerated-pill">
                                    Regenerated
                                </span>

                            @endif

                            <button
                                class="mini-btn"
                                type="button"
                                data-open-modal="postModal{{ $post->id }}"
                            >
                                View Full
                            </button>

                        </div>

                    </div>

                    <div class="post-preview">

                        <p>
                            {{ \Illuminate\Support\Str::limit($post->caption, 240) }}
                        </p>

                    </div>

                    <div class="post-meta-row">

                        <div>

                            <span>
                                Creative Direction
                            </span>

                            <strong>
                                {{ \Illuminate\Support\Str::limit($post->creative_direction, 60) }}
                            </strong>

                        </div>

                        <div>

                            <span>
                                Hashtags
                            </span>

                            <strong>
                                {{ \Illuminate\Support\Str::limit($post->hashtags, 40) }}
                            </strong>

                        </div>

                    </div>

                </div>
                
                <x-modal
                id="postModal{{ $post->id }}"
                title="Generated Campaign Post"
                subtitle="Review and edit the generated marketing content."
            >
            
                <div class="generated-post-detail">
            
                    <div class="generated-detail-block">
            
                        <span>
                            Summary
                        </span>
            
                        <p>
                            {{ $post->summary }}
                        </p>
            
                    </div>
            
                    <form
                        method="POST"
                        action="{{ route('agency.campaign-posts.update', $post) }}"
                    >
            
                        @csrf
                        @method('PATCH')
            
                        <div class="generated-detail-block">
            
                            <span>
                                Caption
                            </span>
            
                            <textarea
                                class="generated-output-textarea"
                                rows="8"
                                name="caption"
                            >{{ $post->caption }}</textarea>
            
                        </div>
            
                        <div class="generated-detail-block">
            
                            <span>
                                Hashtags
                            </span>
            
                            <textarea
                                class="generated-output-textarea"
                                rows="4"
                                name="hashtags"
                            >{{ $post->hashtags }}</textarea>
            
                        </div>
            
                        <div class="generated-detail-block">
            
                            <span>
                                Creative Direction
                            </span>
            
                            <textarea
                                class="generated-output-textarea"
                                rows="5"
                                name="creative_direction"
                            >{{ $post->creative_direction }}</textarea>
            
                        </div>
            
                        <div class="modal-actions">
            
                            <button
                                class="btn btn-regenerate"
                                type="submit"
                            >
                                Save Changes
                            </button>
            
                            <button
                                class="btn btn-secondary"
                                type="button"
                                data-copy-post
                            >
                                Copy Content
                            </button>
            
                            <button
                                class="btn btn-secondary"
                                type="button"
                                data-close-modal
                            >
                                Close
                            </button>
            
                        </div>
            
                    </form>
            
                    <div class="profile-side-divider"></div>
            
                    <form
                        method="POST"
                        action="{{ route('agency.campaign-posts.regenerate', $post) }}"
                        onsubmit="return confirm('Regenerate this post? This will overwrite the current generated content.')"
                    >
            
                        @csrf
            
                        <div class="modal-actions">
            
                            <button
                            class="btn btn-regenerate"
                            type="submit"
                            @disabled($post->regeneration_count >= 1)
                        >
                            ✦
                            {{ $post->regeneration_count >= 1 ? 'Regeneration Used' : 'Regenerate Post' }}
                        </button>
            
                        </div>
            
                    </form>
            
                </div>
            
            </x-modal>

            @empty

                <x-empty-state
                    title="No generated posts yet"
                    description="This campaign does not contain generated output yet."
                />

            @endforelse

        </div>

        <div class="campaign-side-column">

            <div class="table-card sticky-card">

                <h2 class="section-title">
                    Campaign Summary
                </h2>

                <div class="summary-list">

                    <div class="summary-row">
                        <span>Client</span>
                        <strong>{{ $campaign->client?->name }}</strong>
                    </div>

                    <div class="summary-row">
                        <span>Persona</span>
                        <strong>{{ $campaign->persona?->name }}</strong>
                    </div>

                    <div class="summary-row">
                        <span>Status</span>
                        <strong>{{ ucfirst($campaign->status) }}</strong>
                    </div>

                    <div class="summary-row">
                        <span>Posts Requested</span>
                        <strong>{{ $campaign->requested_posts_count }}</strong>
                    </div>

                    <div class="summary-row">

                        <span>
                            Channels
                        </span>

                        <strong>
                            {{ implode(', ', array_map('ucfirst', $campaign->channels)) }}
                        </strong>

                    </div>

                </div>

                <div class="profile-side-divider"></div>

                <div class="campaign-objective-box">

                    <h3>
                        Campaign Objective
                    </h3>

                    <p>
                        {{ $campaign->description ?: $campaign->objective }}
                    </p>

                </div>

                <div class="save-actions">

                    <a
                        href="{{ route('agency.dashboard') }}"
                        class="btn btn-secondary full-btn"
                    >
                        Back To Dashboard
                    </a>

                    <a
                        href="{{ route('agency.clients.show', $campaign->client) }}"
                        class="btn btn-primary full-btn"
                    >
                        View Client
                    </a>

                </div>

            </div>

        </div>

    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', () => {

    document.querySelectorAll('[data-copy-post]')
        .forEach((button) => {

            button.addEventListener('click', () => {

                const modal =
                    button.closest('form');

                const textareas =
                    modal.querySelectorAll('textarea');

                let combined = '';

                textareas.forEach((textarea) => {

                    combined +=
                        textarea.value + '\n\n';
                });

                navigator.clipboard
                    .writeText(combined);

                button.textContent =
                    'Copied!';

                setTimeout(() => {

                    button.textContent =
                        'Copy Content';

                }, 1800);
            });
        });
});
</script>

@endsection