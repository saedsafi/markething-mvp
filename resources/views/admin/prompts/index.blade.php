@extends('layouts.dashboard')

@section('title', 'Prompt Editor - MARKETHING')

@section('page-title', 'Prompt Editor')
@section('page-subtitle', 'Manage the master campaign prompt and per-question AI Assist prompts.')

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
            >
@verbatim
You are MARKETHING's campaign generation engine.

Use the following variables:
{{business_info}}
{{brand_info}}
{{selected_persona}}
{{campaign_objective}}
{{campaign_dates}}
{{channels}}
{{post_count}}

Return structured JSON only.
@endverbatim
            </x-prompt-editor>

            <aside class="prompt-side">

                <div class="table-card">
                    <h2 class="section-title">Variable Reference</h2>

                    <div class="variable-list">
                        <button type="button">@verbatim{{business_info}}@endverbatim</button>
                        <button type="button">@verbatim{{brand_info}}@endverbatim</button>
                        <button type="button">@verbatim{{selected_persona}}@endverbatim</button>
                        <button type="button">@verbatim{{campaign_objective}}@endverbatim</button>
                        <button type="button">@verbatim{{campaign_dates}}@endverbatim</button>
                        <button type="button">@verbatim{{channels}}@endverbatim</button>
                        <button type="button">@verbatim{{post_count}}@endverbatim</button>
                    </div>
                </div>

                <div class="table-card">
                    <h2 class="section-title">Test Prompt Rule</h2>

                    <p class="profile-text">
                        The test prompt is used only with a predetermined test user.
                        It should not affect normal agency users.
                    </p>
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
                            Total posts must match request
                        </div>

                        <div class="checklist-item done">
                            <span>✓</span>
                            Posts must stay inside date range
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
            >
@verbatim
Using {{business_context}}, draft a clear answer for:
{{question_label}}

Also consider the user's extra popup instructions:
{{extra_instructions}}

Match the user's language.
Keep the answer under {{character_limit}} characters.
@endverbatim
            </x-prompt-editor>

            <x-prompt-editor
                badge="Brand Info"
                title="Assist Prompt — Brand Personality"
                description="Used to draft the client's brand personality answer."
                version="v1.1"
            >
@verbatim
Using {{business_context}}, describe the brand personality for:
{{question_label}}

Also consider:
{{extra_instructions}}

Use a concise marketing-focused tone.
@endverbatim
            </x-prompt-editor>

            <x-prompt-editor
                badge="Persona"
                title="Assist Prompt — Persona Description"
                description="Used to draft audience persona descriptions."
                version="v1.0"
            >
@verbatim
Using {{business_context}}, draft a persona answer for:
{{question_label}}

Use the user's extra popup instructions:
{{extra_instructions}}

Return a concise, practical persona description.
@endverbatim
            </x-prompt-editor>

        </div>

    </section>

    <section class="admin-tab-panel" id="promptVersions">

        <x-data-table title="Prompt Version History">

            <table class="dashboard-table">
                <thead>
                    <tr>
                        <th>Prompt</th>
                        <th>Version</th>
                        <th>Type</th>
                        <th>Updated</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td>Master Prompt</td>
                        <td>v1.0</td>
                        <td>Campaign Generation</td>
                        <td>May 2026</td>
                        <td>
                            <button class="mini-btn" type="button" data-view-prompt-history>
                                View
                            </button>                        
                        </td>
                    </tr>

                    <tr>
                        <td>Business Offer Assist</td>
                        <td>v1.2</td>
                        <td>AI Assist</td>
                        <td>May 2026</td>
                        <td>
                            <button class="mini-btn" type="button" data-view-prompt-history>
                                View
                            </button>                       
                        </td>
                    </tr>

                    <tr>
                        <td>Brand Personality Assist</td>
                        <td>v1.1</td>
                        <td>AI Assist</td>
                        <td>May 2026</td>
                        <td>
                            <button class="mini-btn" type="button" data-view-prompt-history>
                                View
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>

        </x-data-table>

    </section>

</div>

<x-modal
    id="promptPreviewModal"
    title="Test Prompt"
    subtitle="This test uses the predetermined test user only."
>
    <div class="prompt-preview-box">
        <strong>Test Output</strong>
        <p>
            The test prompt ran against the predetermined test user. Backend integration will provide the real Claude response later.
        </p>
    </div>
</x-modal>
<x-modal
    id="promptHistoryModal"
    title="Prompt Version History"
    subtitle="Expand a version to view its details."
>

    <div class="version-drawer-list">

        <div class="version-drawer">
            <button class="version-drawer-header" type="button" data-toggle-version>
                <span>Master Prompt — v1.0</span>
                <strong>May 2026</strong>
            </button>

            <div class="version-drawer-body">
                <p class="table-muted">Created on May 7, 2026</p>

                <pre>@verbatim
You are MARKETHING's campaign generation engine.

Use:
{{business_info}}
{{brand_info}}
{{selected_persona}}
{{campaign_objective}}
@endverbatim</pre>
            </div>
        </div>

        <div class="version-drawer">
            <button class="version-drawer-header" type="button" data-toggle-version>
                <span>Master Prompt — v0.9</span>
                <strong>April 2026</strong>
            </button>

            <div class="version-drawer-body">
                <p class="table-muted">Created on April 24, 2026</p>

                <pre>@verbatim
Previous prompt structure for campaign generation.

Variables:
{{business_info}}
{{campaign_dates}}
{{channels}}
@endverbatim</pre>
            </div>
        </div>

        <div class="version-drawer">
            <button class="version-drawer-header" type="button" data-toggle-version>
                <span>Master Prompt — v0.8</span>
                <strong>April 2026</strong>
            </button>

            <div class="version-drawer-body">
                <p class="table-muted">Created on April 10, 2026</p>

                <pre>@verbatim
Initial MVP prompt draft.

Return structured JSON only.
@endverbatim</pre>
            </div>
        </div>

    </div>

</x-modal>

<x-toast id="appToast" title="Prompt Saved" message="Prompt saved successfully." />

@endsection