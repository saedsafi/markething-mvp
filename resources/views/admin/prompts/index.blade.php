@extends('layouts.dashboard')

@section('title', 'Prompt Editor - MARKETHING')

@section('page-title', 'Prompt Editor')
@section('page-subtitle', 'Manage master campaign prompts and per-question AI Assist prompts.')

@section('user-name', 'Founder Admin')
@section('user-role', 'Platform Owner')

@section('dashboard-content')

<div class="admin-prompts-page">

    <div class="admin-tools-tabs">
        <button class="admin-tab active" type="button" data-admin-tab="masterPrompt">
            Master Prompt
        </button>

        <button class="admin-tab" type="button" data-admin-tab="assistPrompts">
            Assist Prompts
        </button>

        <button class="admin-tab" type="button" data-admin-tab="promptVersions">
            Version History
        </button>
    </div>

    <section class="admin-tab-panel active" id="masterPrompt">

        <div class="prompt-layout">

            <x-prompt-editor
                badge="Campaign Generation"
                title="Master Prompt"
                description="This prompt generates full campaigns, post schedules, captions, hashtags, and creative directions."
                version="v1.0"
            >You are MARKETHING's campaign generation engine.

Use the following variables:
{{business_info}}
{{brand_info}}
{{selected_persona}}
{{campaign_objective}}
{{campaign_dates}}
{{channels}}
{{post_count}}

Return structured JSON only.</x-prompt-editor>

            <aside class="prompt-side">

                <div class="table-card">
                    <h2 class="section-title">Variable Reference</h2>

                    <div class="variable-list">
                        <button>{{business_info}}</button>
                        <button>{{brand_info}}</button>
                        <button>{{selected_persona}}</button>
                        <button>{{campaign_objective}}</button>
                        <button>{{campaign_dates}}</button>
                        <button>{{channels}}</button>
                        <button>{{post_count}}</button>
                    </div>
                </div>

                <div class="table-card">
                    <h2 class="section-title">Prompt Rules</h2>

                    <div class="checklist">
                        <div class="checklist-item done">
                            <span>✓</span>
                            JSON output required
                        </div>

                        <div class="checklist-item done">
                            <span>✓</span>
                            One post per day per channel
                        </div>

                        <div class="checklist-item active">
                            <span>!</span>
                            Validate before promotion
                        </div>
                    </div>
                </div>

            </aside>

        </div>

    </section>

    <section class="admin-tab-panel" id="assistPrompts">

        <div class="assist-prompt-grid">

            <x-prompt-editor
                badge="Business Info"
                title="Assist Prompt — What does this business offer?"
                description="Used when agency users click Help me answer this on the business offer field."
                version="v1.2"
            >Using {{business_context}}, draft a clear answer for:
{{question_label}}

Match the user's language.
Keep the answer under {{character_limit}} characters.</x-prompt-editor>

            <x-prompt-editor
                badge="Brand Info"
                title="Assist Prompt — Brand Personality"
                description="Used to draft the client's brand personality answer."
                version="v1.1"
            >Using {{business_context}}, describe the brand personality for:
{{question_label}}

Use a concise marketing-focused tone.</x-prompt-editor>

        </div>

    </section>

    <section class="admin-tab-panel" id="promptVersions">

        <x-data-table title="Prompt Version History">

            <table class="dashboard-table">
                <thead>
                    <tr>
                        <th>Prompt</th>
                        <th>Version</th>
                        <th>Status</th>
                        <th>Updated</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td>Master Prompt</td>
                        <td>v1.0</td>
                        <td><span class="status active-status">Production</span></td>
                        <td>May 2026</td>
                        <td><button class="mini-btn">View</button></td>
                    </tr>

                    <tr>
                        <td>Business Offer Assist</td>
                        <td>v1.2</td>
                        <td><span class="status pending-status">Draft</span></td>
                        <td>May 2026</td>
                        <td><button class="mini-btn">Promote</button></td>
                    </tr>
                </tbody>
            </table>

        </x-data-table>

    </section>

</div>

<x-modal
    id="promptPreviewModal"
    title="Prompt Test Preview"
    subtitle="This is a frontend demo preview result."
>
    <div class="prompt-preview-box">
        <strong>Sample LLM Output</strong>
        <p>
            The prompt produced valid structured campaign output. Backend Claude API integration will provide real results later.
        </p>
    </div>
</x-modal>

<x-toast id="appToast" title="Prompt Saved" message="Prompt action completed successfully." />

@endsection