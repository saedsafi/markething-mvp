@php
    $activeVersion = $template->currentVersion;

    $activeTestVersion = $template->testVersions
        ->firstWhere('is_active', true);

    $latestTestVersion = $template->testVersions->first();

    $testPromptContent =
        old(
            'content',
            $activeTestVersion?->content
                ?? $latestTestVersion?->content
                ?? $activeVersion?->content
        );
@endphp

<div class="table-card prompt-template-card">

    <div class="prompt-card-header">

        <div class="prompt-card-info">

            <div class="prompt-template-top">

                <span class="hero-badge">
                    {{ ucfirst($template->type) }}
                </span>

                @if ($activeVersion)
                    <span class="active-version-pill">
                        Live {{ $activeVersion->version }}
                    </span>
                @endif

                @if ($activeTestVersion)
                    <span class="active-version-pill test-version-pill">
                        Test {{ $activeTestVersion->version }}
                    </span>
                @endif

            </div>

            <h2 class="section-title">
                {{ $template->name }}
            </h2>

            <p class="section-description">
                {{ $template->description ?: 'No description provided.' }}
            </p>

        </div>

        <div class="prompt-card-header-actions">

            <button
                class="btn btn-primary"
                type="button"
                data-open-modal="newVersionModal{{ $template->id }}"
            >
                + New Live Version
            </button>


        </div>
        <div class="prompt-card-header-actions">

        <button
        class="btn btn-edit"
        type="button"
                data-open-modal="testPromptModal{{ $template->id }}"
            >
                + New Test Prompt
        </button>
        </div>

    </div>

    @if ($activeVersion)

        <div class="active-prompt-box">

            <div class="prompt-box-top">

                <div>
                    <span class="prompt-meta-label">
                        Current Live AI Instructions
                    </span>

                    <h3>
                        {{ $activeVersion->version }}
                    </h3>
                </div>

                <span class="status active-status">
                    Active
                </span>

            </div>

            <div class="prompt-preview-box">
                <pre>{{ $activeVersion->content }}</pre>
            </div>

        </div>

    @endif

    <div class="prompt-actions-row">

        <button
            class="btn btn-secondary"
            type="button"
            data-open-modal="historyModal{{ $template->id }}"
        >
            Live History
        </button>

        <button
            class="btn btn-secondary"
            type="button"
            data-open-modal="testHistoryModal{{ $template->id }}"
        >
            Test Prompt History
        </button>



    </div>

</div>

{{-- CREATE LIVE VERSION MODAL --}}

<x-modal
    id="newVersionModal{{ $template->id }}"
    title="Create Live Prompt Version"
    subtitle="Create a new immutable live version for this template."
>
    <form
        method="POST"
        action="{{ route('admin.prompts.versions.store') }}"
    >
        @csrf

        <input
            type="hidden"
            name="prompt_template_id"
            value="{{ $template->id }}"
        >

        <div class="prompt-protection-box">
            <div class="prompt-protection-icon">🔒</div>
        
            <div>
                <strong>Protected system structure</strong>
        
                <p>
                    MARKETHING automatically injects business context, brand information,
                    persona data, campaign details, dates, channels, and output rules.
                    Edit only the natural-language AI instructions below.
                </p>
            </div>
        </div>
        
        <div class="form-group">
            <label class="form-label">
                Live AI Instructions
            </label>
        
            <textarea
                name="content"
                class="form-textarea prompt-editor-textarea"
                placeholder="Write the AI instructions, tone, strategy, and writing rules..."
                required
            >{{ old('content', $activeVersion?->content) }}</textarea>
        
            <p class="input-helper">
                Safe edits: tone, strategy, writing style, language rules, marketing direction.
                Be careful with variables and JSON/output rules.
            </p>
        </div>

        <div class="modal-actions">

            <button class="btn btn-primary" type="submit">
                Create Live Version
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

{{-- TEST PROMPT MODAL --}}

<x-modal
    id="testPromptModal{{ $template->id }}"
    title="Create Test Prompt Version"
    subtitle="Safely test prompt changes before promoting them to live."
>

    <div class="prompt-protection-box">
        <div class="prompt-protection-icon">🧪</div>
    
        <div>
            <strong>Test prompt safety</strong>
    
            <p>
                This version is used only by users marked with
                <code>uses_test_prompts = true</code>. Edit the AI instructions below,
                then save it as a test version before promoting it to live.
            </p>
        </div>
    </div>
    <form
        method="POST"
        action="{{ route('admin.prompts.test-versions.store') }}"
        >
        @csrf

        <div class="form-group">
            <label class="form-label">
                Test AI Instructions
            </label>

            <textarea
                name="content"
                class="form-textarea prompt-editor-textarea"
                required
            >{{ $testPromptContent }}</textarea>

            <p class="input-helper">
                This is separate from the live prompt. Save it as a test version before promoting changes.
            </p>
        </div>

        <div class="modal-actions">
            <input
                type="hidden"
                name="prompt_template_id"
                value="{{ $template->id }}"
            >

            <button
                class="btn btn-primary"
                type="submit"
            >
                Save Test Version
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

{{-- LIVE HISTORY MODAL --}}

<x-modal
    id="historyModal{{ $template->id }}"
    title="Live Prompt Version History"
    subtitle="Inspect all immutable live versions for this template."
    class="version-history-modal"
>
    <div class="prompt-history-list">

        @foreach ($template->versions as $version)

            <div class="prompt-history-item">

                <div class="prompt-history-header">

                    <button
                        class="prompt-history-toggle"
                        type="button"
                    >
                        <div class="prompt-history-version-info">
                            <strong>{{ $version->version }}</strong>

                            <p>
                                {{ $version->created_at->format('M d, Y · H:i') }}
                            </p>
                        </div>
                    </button>

                    <div class="prompt-history-actions">

                        @if ($version->is_active)

                            <span class="status active-status">
                                Active
                            </span>

                        @else

                            <form
                                method="POST"
                                action="{{ route('admin.prompts.versions.activate', $version) }}"
                            >
                                @csrf
                                @method('PATCH')

                                <button
                                    class="mini-btn success"
                                    type="submit"
                                >
                                    Activate
                                </button>
                            </form>

                        @endif

                    </div>

                </div>

                <div class="prompt-history-content">

                    <div class="history-prompt-preview">
                        <pre>{{ $version->content }}</pre>
                    </div>

                </div>

            </div>

        @endforeach

    </div>
</x-modal>

{{-- TEST HISTORY MODAL --}}

<x-modal
    id="testHistoryModal{{ $template->id }}"
    title="Test Prompt Version History"
    subtitle="Inspect saved test prompt versions for this template."
    class="version-history-modal"
>
    <div class="prompt-history-list">

        @forelse ($template->testVersions as $version)

            <div class="prompt-history-item">

                <div class="prompt-history-header">

                    <button
                        class="prompt-history-toggle"
                        type="button"
                    >
                        <div class="prompt-history-version-info">
                            <strong>{{ $version->version }}</strong>

                            <p>
                                {{ $version->created_at->format('M d, Y · H:i') }}
                            </p>
                        </div>
                    </button>

                    <div class="prompt-history-actions">

                        @if ($version->is_active)

                            <span class="status active-status">
                                Active Test
                            </span>

                            @else

                            <form
                                method="POST"
                                action="{{ route('admin.prompts.test-versions.activate', $version) }}"
                            >
                                @csrf
                                @method('PATCH')
                        
                                <button
                                    class="mini-btn activate"
                                    type="submit"
                                >
                                    Activate Test
                                </button>
                            </form>
                        
                        @endif
                        
                        <form
                            method="POST"
                            action="{{ route('admin.prompts.test-versions.promote', $version) }}"
                        >
                            @csrf
                        
                            <button
                                class="mini-btn"
                                type="submit"
                                onclick="return confirm('Promote this test prompt to a new Live version?')"
                            >
                                Promote To Live
                            </button>
                        </form>

                    </div>

                </div>

                <div class="prompt-history-content">

                    <div class="history-prompt-preview">
                        <pre>{{ $version->content }}</pre>
                    </div>

                </div>

            </div>

        @empty

            <x-empty-state
                title="No test prompt versions yet"
                description="Save a test prompt version before viewing history."
            />

        @endforelse

    </div>
</x-modal>