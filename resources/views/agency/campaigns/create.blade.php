@extends('layouts.dashboard')

@section('title', 'Create Campaign - MARKETHING')

@section('page-title', 'Create New Campaign')
@section('page-subtitle', 'Select a client, choose a persona, define campaign details, and generate AI-powered posts.')

@section('user-name', 'Nova Marketing')
@section('user-role', 'Agency Account')

@section('dashboard-content')

<div class="campaign-builder-page">

    <x-stepper
        :steps="['Client', 'Persona', 'Campaign Details', 'Review & Generate']"
        :active="1"
    />

    <div class="campaign-builder-grid">

        <aside class="campaign-builder-side">

            <x-campaign-step-card
                number="1"
                title="Select Client"
                description="Choose the client profile this campaign belongs to."
                :active="true"
            />

            <x-campaign-step-card
                number="2"
                title="Select Persona"
                description="Pick one audience persona from the selected client."
            />

            <x-campaign-step-card
                number="3"
                title="Campaign Details"
                description="Set objective, channels, dates, and post count."
            />

            <x-campaign-step-card
                number="4"
                title="Review & Generate"
                description="Confirm everything and start AI generation."
            />

        </aside>

        <main class="campaign-builder-main">

            <form id="campaignBuilderForm">

                <section class="table-card campaign-section" data-campaign-section="1">

                    <div class="builder-section-header">
                        <span>Step 1</span>
                        <h2>Select Client Profile</h2>
                        <p>
                            Choose one of your existing client profiles. Campaigns capture a snapshot
                            of the selected client data when generated.
                        </p>
                    </div>

                    <div class="selection-grid">

                        <label class="select-card">
                            <input type="radio" name="client" value="bloom-cafe" checked>

                            <div class="client-logo">B</div>

                            <div>
                                <h3>Bloom Café</h3>
                                <p>Food & Beverage · 3 personas</p>
                            </div>
                        </label>

                        <label class="select-card">
                            <input type="radio" name="client" value="luna-boutique">

                            <div class="client-logo">L</div>

                            <div>
                                <h3>Luna Boutique</h3>
                                <p>Fashion · 2 personas</p>
                            </div>
                        </label>

                        <label class="select-card disabled">
                            <input type="radio" name="client" value="nova-fitness" disabled>

                            <div class="client-logo">N</div>

                            <div>
                                <h3>Nova Fitness</h3>
                                <p>Needs at least one persona</p>
                            </div>
                        </label>

                    </div>

                    <div class="campaign-section-actions">
                        <button class="btn btn-primary" type="button" data-campaign-next>
                            Continue
                        </button>
                    </div>

                </section>

                <section class="table-card campaign-section hidden" data-campaign-section="2">

                    <div class="builder-section-header">
                        <span>Step 2</span>
                        <h2>Select Audience Persona</h2>
                        <p>
                            Select the audience persona this campaign should speak to.
                        </p>
                    </div>

                    <div class="selection-grid">

                        <label class="select-card">
                            <input type="radio" name="persona" value="young-professional" checked>

                            <div class="persona-avatar">YP</div>

                            <div>
                                <h3>Young Professional</h3>
                                <p>25-35 · Instagram-focused · Lifestyle buyer</p>
                            </div>
                        </label>

                        <label class="select-card">
                            <input type="radio" name="persona" value="friend-group">

                            <div class="persona-avatar">FG</div>

                            <div>
                                <h3>Friend Group Planner</h3>
                                <p>20-30 · Social gatherings · Visual content</p>
                            </div>
                        </label>

                        <label class="select-card">
                            <input type="radio" name="persona" value="remote-freelancer">

                            <div class="persona-avatar">RF</div>

                            <div>
                                <h3>Remote Freelancer</h3>
                                <p>24-40 · Calm places · Productivity</p>
                            </div>
                        </label>

                    </div>

                    <div class="campaign-section-actions">
                        <button class="btn btn-secondary" type="button" data-campaign-prev>
                            Back
                        </button>

                        <button class="btn btn-primary" type="button" data-campaign-next>
                            Continue
                        </button>
                    </div>

                </section>

                <section class="table-card campaign-section hidden" data-campaign-section="3">

                    <div class="builder-section-header">
                        <span>Step 3</span>
                        <h2>Campaign Details</h2>
                        <p>
                            Define the campaign objective, dates, channels, and number of posts.
                        </p>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Campaign Name</label>
                        <input
                            type="text"
                            class="form-input"
                            id="campaignName"
                            placeholder="Summer Launch"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label class="form-label">Campaign Objective</label>

                        <select class="form-input" id="campaignObjective">
                            <option>Awareness</option>
                            <option>Launch</option>
                            <option>Promotion</option>
                            <option>Engagement</option>
                            <option>Seasonal Campaign</option>
                        </select>
                    </div>

                    <div class="campaign-form-grid">

                        <div class="form-group">
                            <label class="form-label">Start Date</label>
                            <input type="date" class="form-input" id="campaignStartDate">
                        </div>

                        <div class="form-group">
                            <label class="form-label">End Date</label>
                            <input type="date" class="form-input" id="campaignEndDate">
                        </div>

                    </div>

                    <div class="validation-box hidden" id="dateValidationBox">
                        End date must be after start date. Maximum campaign range is 90 days.
                    </div>

                    <div class="form-group">
                        <label class="form-label">Channels</label>

                        <div class="channel-grid">
                            <x-channel-toggle
                                value="instagram"
                                label="Instagram"
                                description="Reels, posts, carousels, captions"
                            />

                            <x-channel-toggle
                                value="facebook"
                                label="Facebook"
                                description="Posts, captions, engagement content"
                            />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Total Number of Posts</label>

                        <input
                            type="number"
                            class="form-input"
                            id="postCount"
                            min="1"
                            placeholder="Example: 12"
                        >

                        <p class="input-helper" id="postLimitHint">
                            Select dates and channels to calculate the maximum allowed posts.
                        </p>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Campaign Description</label>

                        <textarea
                            class="form-textarea"
                            id="campaignDescription"
                            placeholder="Describe the offer, message, campaign theme, or content direction..."
                        ></textarea>

                        <p class="input-helper">
                            AI Assist is not available on campaign creation fields in v1.
                        </p>
                    </div>

                    <div class="campaign-section-actions">
                        <button class="btn btn-secondary" type="button" data-campaign-prev>
                            Back
                        </button>

                        <button class="btn btn-primary" type="button" data-campaign-next>
                            Review Campaign
                        </button>
                    </div>

                </section>

                <section class="table-card campaign-section hidden" data-campaign-section="4">

                    <div class="builder-section-header">
                        <span>Step 4</span>
                        <h2>Review & Generate</h2>
                        <p>
                            Review your campaign setup before triggering AI generation.
                        </p>
                    </div>

                    <div class="review-grid">

                        <div class="review-item">
                            <span>Client</span>
                            <strong>Bloom Café</strong>
                        </div>

                        <div class="review-item">
                            <span>Persona</span>
                            <strong>Young Professional</strong>
                        </div>

                        <div class="review-item">
                            <span>Objective</span>
                            <strong id="reviewObjective">Awareness</strong>
                        </div>

                        <div class="review-item">
                            <span>Date Range</span>
                            <strong id="reviewDates">Not selected</strong>
                        </div>

                        <div class="review-item">
                            <span>Channels</span>
                            <strong id="reviewChannels">Not selected</strong>
                        </div>

                        <div class="review-item">
                            <span>Total Posts</span>
                            <strong id="reviewPosts">0</strong>
                        </div>

                    </div>

                    <div class="generation-warning">
                        <strong>Before generating:</strong>
                        Campaign creation must be completed in one session. If you leave this page,
                        your current progress may be discarded.
                    </div>

                    <div class="campaign-section-actions">
                        <button class="btn btn-secondary" type="button" data-campaign-prev>
                            Back
                        </button>

                        <button class="btn btn-primary" type="button" id="generateCampaignBtn">
                            ✦ Generate Campaign
                        </button>
                    </div>

                </section>

            </form>

        </main>

    </div>

</div>

<div class="generation-overlay" id="generationOverlay">

    <div class="generation-card">

        <div class="generation-loader"></div>

        <h2>Generating Your Campaign</h2>

        <p>
            MARKETHING is creating your campaign posts, captions, hashtags,
            creative directions, and schedule.
        </p>

        <div class="generation-steps">
            <span class="active">Preparing prompt</span>
            <span>Calling AI engine</span>
            <span>Validating schedule</span>
            <span>Building post cards</span>
        </div>

    </div>

</div>

@endsection