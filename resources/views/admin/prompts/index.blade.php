@extends('layouts.dashboard')

@section('title', 'Prompt Editor - MARKETHING')

@section('page-title', 'Prompt Editor')

@section(
    'page-subtitle',
    'Manage master and assist AI prompt templates, versions, testing, and activation.'
)

@section('user-name', auth()->user()->name ?? 'Founder Admin')
@section('user-role', 'Platform Owner')

@section('dashboard-content')

@php
    $masterTemplates = $templates->where('type', 'master');
    $assistTemplates = $templates->where('type', 'assist');
@endphp

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
                    <h2 class="section-title">Prompt Test Result</h2>
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

    <div class="prompt-tabs-card">

        <div class="prompt-tabs-header">

            <button
                class="prompt-tab-btn active"
                type="button"
                data-prompt-tab="master"
            >
                Master Prompt
            </button>

            <button
                class="prompt-tab-btn"
                type="button"
                data-prompt-tab="assist"
            >
                Assist Prompts
            </button>

        </div>

    </div>

    <div
        class="prompt-tab-panel active"
        data-prompt-panel="master"
    >

        @forelse ($masterTemplates as $template)

            @include('admin.prompts.partials.prompt-card', [
                'template' => $template,
            ])

        @empty

            <x-empty-state
                title="No master prompt found"
                description="Seed or create a master campaign prompt before generating campaigns."
            />

        @endforelse

    </div>

    <div
        class="prompt-tab-panel"
        data-prompt-panel="assist"
    >

        @forelse ($assistTemplates as $template)

            @include('admin.prompts.partials.prompt-card', [
                'template' => $template,
            ])

        @empty

            <x-empty-state
                title="No assist prompts found"
                description="Seed assist prompts for AI Assist eligible profile fields."
            />

        @endforelse

    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', () => {

    document.querySelectorAll('[data-prompt-tab]')
        .forEach((button) => {

            button.addEventListener('click', () => {

                const tab =
                    button.dataset.promptTab;

                document.querySelectorAll('[data-prompt-tab]')
                    .forEach((item) => {
                        item.classList.remove('active');
                    });

                document.querySelectorAll('[data-prompt-panel]')
                    .forEach((panel) => {
                        panel.classList.remove('active');
                    });

                button.classList.add('active');

                document
                    .querySelector(`[data-prompt-panel="${tab}"]`)
                    ?.classList.add('active');
            });
        });

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