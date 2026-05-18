@extends('layouts.dashboard')

@section('title', 'Prompt Editor - MARKETHING')

@section('page-title', 'Prompt Editor')

@section(
    'page-subtitle',
    'Manage AI prompt templates, versions, testing, and activation.'
)

@section('user-name', auth()->user()->name ?? 'Founder Admin')
@section('user-role', 'Platform Owner')

@section('dashboard-content')

<div class="prompt-editor-page">

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

    @if (session('test_result'))

        @php
            $test = session('test_result');
        @endphp

        <div class="table-card test-result-card">

            <div class="section-header">

                <div>

                    <h2 class="section-title">
                        Prompt Test Result
                    </h2>

                    <p class="section-description">
                        Simulated AI response from the current prompt.
                    </p>

                </div>

            </div>

            <div class="prompt-test-grid">

                <div class="prompt-test-block">

                    <span>Assembled Prompt</span>

                    <pre>{{ $test['assembled_prompt'] }}</pre>

                </div>

                <div class="prompt-test-block">

                    <span>Generated Response</span>

                    <pre>{{ $test['response'] }}</pre>

                </div>

            </div>

            <div class="prompt-test-meta">

                <div>
                    <span>Status</span>
                    <strong>{{ ucfirst($test['status']) }}</strong>
                </div>

                <div>
                    <span>Tokens</span>
                    <strong>{{ $test['tokens'] }}</strong>
                </div>

                <div>
                    <span>Latency</span>
                    <strong>{{ $test['latency'] }}</strong>
                </div>

            </div>

        </div>

    @endif

    <div class="prompt-template-list">

        @forelse ($templates as $template)

            @php
                $activeVersion = $template->currentVersion;
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
                                    Active {{ $activeVersion->version }}
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
                            + New Version
                        </button>

                    </div>

                </div>

                @if ($activeVersion)

                    <div class="active-prompt-box">

                        <div class="prompt-box-top">

                            <div>

                                <span class="prompt-meta-label">
                                    Current Active Prompt
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
                        View Version History
                    </button>

                    <button
                        class="btn btn-primary"
                        type="button"
                        data-open-modal="testPromptModal{{ $template->id }}"
                    >
                        Test Prompt
                    </button>

                </div>

            </div>

            <!-- CREATE VERSION MODAL -->

            <x-modal
                id="newVersionModal{{ $template->id }}"
                title="Create Prompt Version"
                subtitle="Create a new immutable version for this template."
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

                    <div class="form-group">

                        <label class="form-label">
                            Prompt Content
                        </label>

                        <textarea
                            name="content"
                            class="form-textarea prompt-editor-textarea"
                            required
                        >{{ old('content', $activeVersion?->content) }}</textarea>

                    </div>

                    <div class="form-group">

                        <label class="form-label">
                            Version Notes
                        </label>

                        <textarea
                            name="notes"
                            class="form-textarea"
                            placeholder="Describe changes in this version..."
                        >{{ old('notes') }}</textarea>

                    </div>

                    <div class="modal-actions">

                        <button class="btn btn-primary" type="submit">
                            Create Version
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

            <!-- TEST MODAL -->

            <x-modal
                id="testPromptModal{{ $template->id }}"
                title="Test Prompt"
                subtitle="Run a simulated AI prompt test."
            >

                <form
                    method="POST"
                    action="{{ route('admin.prompts.test') }}"
                >

                    @csrf

                    <div class="form-group">

                        <label class="form-label">
                            Prompt
                        </label>

                        <textarea
                            name="prompt"
                            class="form-textarea prompt-editor-textarea"
                            required
                        >{{ $activeVersion?->content }}</textarea>

                    </div>

                    <div class="form-group">

                        <label class="form-label">
                            Test Input
                        </label>

                        <textarea
                            name="test_input"
                            class="form-textarea"
                            placeholder="Business context, campaign goals, persona, etc..."
                            required
                        ></textarea>

                    </div>

                    <div class="modal-actions">

                        <button class="btn btn-primary" type="submit">
                            Run Test
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

            <!-- HISTORY MODAL -->

            <x-modal
                id="historyModal{{ $template->id }}"
                title="Prompt Version History"
                subtitle="Inspect all immutable versions for this template."
                class="version-history-modal"
            >
            <div class="modal-body">

                <div class="prompt-history-list ">

                    @foreach ($template->versions as $version)

                    <div class="prompt-history-item">

                        <div class="prompt-history-header">
                    
                            <button
                                class="prompt-history-toggle"
                                type="button"
                            >
                    
                                <div class="prompt-history-version-info">
                    
                                    <strong>
                                        {{ $version->version }}
                                    </strong>
                    
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
                    
                            @if ($version->notes)
                    
                                <div class="history-notes-box">
                    
                                    <span>
                                        Version Notes
                                    </span>
                    
                                    <p>
                                        {{ $version->notes }}
                                    </p>
                    
                                </div>
                    
                            @endif
                    
                            <div class="history-prompt-preview">
                    
                                <pre>{{ $version->content }}</pre>
                    
                            </div>
                    
                        </div>
                    
                    </div>

                    @endforeach

                </div>

            </div>
            
            </x-modal>

        @empty

            <x-empty-state
                title="No prompt templates found"
                description="Seed prompt templates before using the prompt editor."
            />

        @endforelse

    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
    
        document.querySelectorAll('.prompt-history-toggle')
            .forEach((toggle) => {
    
                toggle.addEventListener('click', () => {
    
                    const item =
                        toggle.closest('.prompt-history-item');
    
                    item.classList.toggle('open');
                });
            });
    });
    </script>

@endsection