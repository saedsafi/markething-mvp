@extends('layouts.dashboard')

@section('title', 'Create Client Profile - MARKETHING')

@section('page-title', 'Create Client Profile')
@section('page-subtitle', 'Build a complete client profile for AI-powered campaign generation.')

@section('user-name', 'Nova Marketing')
@section('user-role', 'Agency Account')

@section('dashboard-content')

<div class="client-create-page">

    <x-stepper
        :steps="['Business Context', 'Business Info', 'Brand Info', 'Personas', 'Review']"
        :active="1"
    />

    <div class="profile-builder-grid">

        <div class="profile-builder-main">

            <div class="table-card">

                <div class="builder-section-header">
                    <span>Step 1</span>
                    <h2>Business Context</h2>
                    <p>
                        Paste a business description, bio, captions, “about us” text,
                        or any useful background. AI Assist will use this as the context source.
                    </p>
                </div>

                <div class="form-group">
                    <label class="form-label">Business Context</label>

                    <textarea
                        class="form-textarea business-context-input"
                        maxlength="5000"
                        placeholder="Paste business description, social captions, bio, or any useful background..."
                    ></textarea>

                    <div class="char-counter">
                        <span>0/5000 characters</span>
                    </div>
                </div>

                <details class="examples-box">
                    <summary>Examples of what to paste</summary>

                    <ul>
                        <li>Instagram bio and 3-5 recent captions.</li>
                        <li>About us paragraph from the business website.</li>
                        <li>A short description written by the business owner.</li>
                    </ul>
                </details>

            </div>

            <div class="table-card">

                <div class="builder-section-header">
                    <span>Step 2</span>
                    <h2>Business Info</h2>
                    <p>
                        These questions define what the client does, what they sell,
                        and how they serve customers.
                    </p>
                </div>

                <div class="form-group">
                    <label class="form-label">Business Name</label>
                    <input type="text" class="form-input" placeholder="Bloom Café">
                </div>

                <div class="form-group">
                    <label class="form-label">Industry</label>

                    <select class="form-input">
                        <option>Food & Beverage</option>
                        <option>Fashion</option>
                        <option>Beauty</option>
                        <option>Fitness</option>
                        <option>Other</option>
                    </select>
                </div>

                <x-ai-assist-field
                    label="What does this business offer?"
                    placeholder="Describe the products, services, or experience..."
                    max="700"
                    helper="Tell us what kind of answer you want: simple, premium, playful, detailed, or short."
                />

            </div>

            <div class="table-card">

                <div class="builder-section-header">
                    <span>Step 3</span>
                    <h2>Brand Info</h2>
                    <p>
                        Capture the client’s tone, personality, values, and communication style.
                    </p>
                </div>

                <div class="form-group">
                    <label class="form-label">Brand Voice</label>

                    <select class="form-input">
                        <option>Warm and friendly</option>
                        <option>Luxury and refined</option>
                        <option>Bold and energetic</option>
                        <option>Professional and trustworthy</option>
                    </select>
                </div>

                <x-ai-assist-field
                    label="Describe the brand personality"
                    placeholder="Example: elegant, warm, youthful, premium..."
                    max="700"
                    helper="Add notes about the desired tone, audience, or brand feeling before generating."
                />

                <x-ai-assist-field
                    label="What values should the brand communicate?"
                    placeholder="Example: quality, comfort, creativity, trust..."
                    max="700"
                    helper="Mention the values or emotional message you want the brand to communicate."
                />

            </div>

            <div class="table-card">

                <div class="builder-section-header">
                    <span>Step 4</span>
                    <h2>Audience Personas</h2>
                    <p>
                        Add at least one persona before using this client in campaigns.
                        You can add up to 5 personas per client.
                    </p>
                </div>

                <div class="persona-form-grid">

                    <div class="form-group">
                        <label class="form-label">Persona Name</label>
                        <input type="text" class="form-input" placeholder="Busy Young Professional">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Age Range</label>
                        <input type="text" class="form-input" placeholder="25-35">
                    </div>

                </div>

                <x-ai-assist-field
                    label="Describe this persona"
                    placeholder="Describe interests, needs, buying motivations..."
                    max="700"
                    helper="Add extra details about the target audience, their lifestyle, pain points, or buying motivations."
                />

                <button class="btn btn-secondary" type="button" data-add-persona>
                    + Add Persona
                </button>

            </div>

            <div class="builder-actions">
                <button class="btn btn-secondary" type="button" data-save-draft>
                    Save Draft
                </button>

                <button class="btn btn-primary" type="button" data-save-client>
                    Save Client Profile
                </button>
            </div>

        </div>

        <aside class="profile-builder-side">

            <div class="table-card">
                <h2 class="section-title">Profile Checklist</h2>

                <div class="checklist">
                    <div class="checklist-item active">
                        <span>1</span>
                        Business context
                    </div>

                    <div class="checklist-item">
                        <span>2</span>
                        Business info
                    </div>

                    <div class="checklist-item">
                        <span>3</span>
                        Brand info
                    </div>

                    <div class="checklist-item">
                        <span>4</span>
                        At least one persona
                    </div>
                </div>
            </div>

            <div class="table-card ai-tip-card">
                <div class="security-icon">✦</div>

                <h3>AI Assist Tip</h3>

                <p>
                    Add a strong Business Context first. AI Assist stays disabled until this field has text.
                </p>
            </div>

        </aside>

    </div>

</div>

<x-modal
    id="aiAssistModal"
    title="Help Me Answer This"
    subtitle="Add extra guidance for this specific answer."
>

    <div class="ai-modal-content">

        <div class="ai-modal-target">
            <span>Field</span>
            <strong id="aiAssistFieldLabel">Selected field</strong>
        </div>

        <p class="ai-modal-helper" id="aiAssistHelperText">
            Add any details that can help MARKETHING draft a better answer.
        </p>

        <div class="form-group">
            <label class="form-label">Extra Instructions</label>

            <textarea
                class="form-textarea"
                id="aiAssistExtraInput"
                placeholder="Example: Make it sound premium, friendly, and suitable for Instagram..."
            ></textarea>
        </div>

        <div class="modal-actions">
            <button class="btn btn-primary" type="button" id="submitAiAssist">
                ✦ Generate Answer
            </button>

            <button class="btn btn-secondary" type="button" data-close-modal>
                Cancel
            </button>
        </div>

    </div>

</x-modal>

<x-toast
    id="appToast"
    title="Saved"
    message="Your action was completed successfully."
/>

<x-toast
    id="aiAssistToast"
    title="AI Draft Ready"
    message="The answer was drafted successfully."
/>

@endsection