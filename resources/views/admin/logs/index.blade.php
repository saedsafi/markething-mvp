@extends('layouts.dashboard')

@section('title', 'LLM Logs - MARKETHING')

@section('page-title', 'LLM Call Logs')
@section('page-subtitle', 'Inspect AI requests, responses, latency, token usage, cost estimates, retries, and failures.')

@section('user-name', auth()->user()->name ?? 'Founder Admin')
@section('user-role', 'Platform Owner')

@section('dashboard-content')

<div class="admin-logs-page">

    @if (session('success'))
        <div class="validation-box success-box">
            {{ session('success') }}
        </div>
    @endif

    <x-data-table title="LLM Calls">
        <form
        method="GET"
        action="{{ route('admin.logs.index') }}"
        class="agency-filters"
        id="llmLogsFiltersForm"
    >
        <select name="call_type" class="form-select">
            <option value="">All Call Types</option>
    
            <option value="campaign_generation" @selected(request('call_type') === 'campaign_generation')>
                Campaign Generation
            </option>
    
            <option value="post_regeneration" @selected(request('call_type') === 'post_regeneration')>
                Post Regeneration
            </option>
    
            <option value="ai_assist" @selected(request('call_type') === 'ai_assist')>
                AI Assist
            </option>
    
            <option value="prompt_test" @selected(request('call_type') === 'prompt_test')>
                Prompt Test
            </option>
        </select>
    
        <select name="status" class="form-select">
            <option value="">All Statuses</option>
    
            <option value="success" @selected(request('status') === 'success')>
                Success
            </option>
    
            <option value="failed" @selected(request('status') === 'failed')>
                Failed
            </option>
        </select>
    
        <select name="user_id" class="form-select">
            <option value="">All Agencies</option>
    
            @foreach ($agencies as $agency)
                <option value="{{ $agency->id }}" @selected((string) request('user_id') === (string) $agency->id)>
                    {{ $agency->name }}
                </option>
            @endforeach
        </select>
    
        <select name="campaign_id" class="form-select">
            <option value="">All Campaigns</option>
    
            @foreach ($campaigns as $campaign)
                <option value="{{ $campaign->id }}" @selected((string) request('campaign_id') === (string) $campaign->id)>
                    {{ $campaign->name }}
                </option>
            @endforeach
        </select>
    
        <div class="date-range-filter">
            <label>Date Range</label>
        
            <div class="date-range-inputs">
                <input
                    type="date"
                    name="date_from"
                    value="{{ request('date_from') }}"
                >
        
                <span>→</span>
        
                <input
                    type="date"
                    name="date_to"
                    value="{{ request('date_to') }}"
                >
            </div>
        </div>

        @if (
            request()->filled('call_type') ||
            request()->filled('status') ||
            request()->filled('user_id') ||
            request()->filled('campaign_id') ||
            request()->filled('date_from') ||
            request()->filled('date_to')
        )

            <a
                href="{{ route('admin.logs.index') }}"
                class="btn btn-secondary"
            >
                Clear Filters
            </a>

        @endif
    
        <a
            href="{{ route('admin.logs.index') }}"
            class="btn btn-secondary"
        >
            Clear
        </a>
    </form>
        <table class="dashboard-table">

            <thead>
                <tr>
                    <th>Type</th>
                    <th>User</th>
                    <th>Provider</th>
                    <th>Tokens</th>
                    <th>Cost</th>
                    <th>Retries</th>
                    <th>Latency</th>
                    <th>Status</th>
                    <th>Details</th>
                </tr>
            </thead>

            <tbody>

                @forelse ($logs as $log)

                    <tr>
                        <td>
                            <strong>
                                {{ str_replace('_', ' ', ucfirst($log->call_type)) }}
                            </strong>

                            <p class="table-muted">
                                {{ $log->created_at->format('M d, Y · H:i') }}
                            </p>
                        </td>

                        <td>
                            {{ $log->user?->email ?? 'System' }}
                        </td>

                        <td>
                            <strong>{{ $log->provider ?? '—' }}</strong>

                            <p class="table-muted">
                                {{ $log->model ?? '—' }}
                            </p>
                        </td>

                        <td>
                            <strong>
                                {{ number_format(($log->input_tokens ?? 0) + ($log->output_tokens ?? 0)) }}
                            </strong>

                            <p class="table-muted">
                                In: {{ number_format($log->input_tokens ?? 0) }}
                                /
                                Out: {{ number_format($log->output_tokens ?? 0) }}
                            </p>
                        </td>

                        <td>
                            <strong>
                                ${{ number_format((float) ($log->estimated_cost_usd ?? 0), 6) }}
                            </strong>
                        </td>

                        <td>
                            {{ $log->retry_count ?? 0 }}
                        </td>

                        <td>
                            {{ number_format($log->latency_ms ?? 0) }} ms
                        </td>

                        <td>
                            @if ($log->status === 'success')
                                <span class="status active-status">
                                    Success
                                </span>
                            @else
                                <span class="status suspended-status">
                                    Failed
                                </span>
                            @endif
                        </td>

                        <td>
                            <button
                                class="mini-btn"
                                type="button"
                                data-open-modal="logModal{{ $log->id }}"
                            >
                                View
                            </button>
                        </td>
                    </tr>

                    <x-modal
                        id="logModal{{ $log->id }}"
                        title="LLM Log Detail"
                        subtitle="Inspect request, response, cost, retry count, and errors."
                    >

                        <div class="llm-log-detail">

                            <div class="log-metadata-grid">

                                <div>
                                    <span>Provider</span>
                                    <strong>{{ $log->provider ?? '—' }}</strong>
                                </div>

                                <div>
                                    <span>Model</span>
                                    <strong>{{ $log->model ?? '—' }}</strong>
                                </div>

                                <div>
                                    <span>Status</span>
                                    <strong>{{ ucfirst($log->status) }}</strong>
                                </div>

                                <div>
                                    <span>Latency</span>
                                    <strong>{{ number_format($log->latency_ms ?? 0) }} ms</strong>
                                </div>

                                <div>
                                    <span>Input Tokens</span>
                                    <strong>{{ number_format($log->input_tokens ?? 0) }}</strong>
                                </div>

                                <div>
                                    <span>Output Tokens</span>
                                    <strong>{{ number_format($log->output_tokens ?? 0) }}</strong>
                                </div>

                                <div>
                                    <span>Estimated Cost</span>
                                    <strong>${{ number_format((float) ($log->estimated_cost_usd ?? 0), 6) }}</strong>
                                </div>

                                <div>
                                    <span>Retry Count</span>
                                    <strong>{{ $log->retry_count ?? 0 }}</strong>
                                </div>

                            </div>

                            <div class="profile-side-divider"></div>

                            <div class="log-payload-section">
                                <h3>Assembled Prompt</h3>

                                <pre>{{ $log->assembled_prompt }}</pre>
                            </div>

                            <div class="log-payload-section">
                                <h3>Response</h3>

                                <pre>{{ $log->response }}</pre>
                            </div>

                            @if ($log->error_message)
                                <div class="log-error-box">
                                    <h3>Error</h3>

                                    <p>{{ $log->error_message }}</p>
                                </div>
                            @endif

                            <div class="modal-actions">
                                <button
                                    class="btn btn-secondary"
                                    type="button"
                                    data-close-modal
                                >
                                    Close
                                </button>
                            </div>

                        </div>

                    </x-modal>

                @empty

                    <tr>
                        <td colspan="9">
                            No LLM logs found.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </x-data-table>

    <div class="pagination-wrapper">
        {{ $logs->links() }}
    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('llmLogsFiltersForm');
    
        if (!form) {
            return;
        }
    
        form
            .querySelectorAll('select, input')
            .forEach((field) => {
                field.addEventListener('change', () => {
                    form.submit();
                });
            });
    });
    </script>

@endsection