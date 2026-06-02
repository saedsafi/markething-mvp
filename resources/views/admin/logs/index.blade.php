@extends('layouts.dashboard')

@section('title', 'LLM Logs - MARKETHING')

@section('page-title', 'LLM Call Logs')

@section(
    'page-subtitle',
    'Inspect AI requests, responses, latency, token usage, and failures.'
)

@section('user-name', auth()->user()->name ?? 'Founder Admin')
@section('user-role', 'Platform Owner')

@section('dashboard-content')

<div class="admin-logs-page">

    @if (session('success'))
        <div class="validation-box success-box">
            {{ session('success') }}
        </div>
    @endif

    <x-data-table title=" LLM Calls">

        <table class="dashboard-table">

            <thead>

                <tr>

                    <th>Type</th>

                    <th>User</th>

                    <th>Provider</th>

                    <th>Tokens</th>

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
                                {{ str_replace('_', ' ', ucfirst($log->type)) }}
                            </strong>

                            <p class="table-muted">
                                {{ $log->created_at->format('M d, Y · H:i') }}
                            </p>

                        </td>

                        <td>

                            {{ $log->user?->email ?? 'System' }}

                        </td>

                        <td>

                            <strong>
                                {{ $log->provider }}
                            </strong>

                            <p class="table-muted">
                                {{ $log->model }}
                            </p>

                        </td>

                        <td>

                            <strong>
                                {{ number_format($log->tokens_input + $log->tokens_output) }}
                            </strong>

                            <p class="table-muted">
                                In:
                                {{ number_format($log->tokens_input) }}
                                /
                                Out:
                                {{ number_format($log->tokens_output) }}
                            </p>

                        </td>

                        <td>

                            {{ number_format($log->latency_ms) }} ms

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
                        subtitle="Inspect request and response payloads."
                    >

                        <div class="llm-log-detail">

                            <div class="log-metadata-grid">

                                <div>

                                    <span>Provider</span>

                                    <strong>
                                        {{ $log->provider }}
                                    </strong>

                                </div>

                                <div>

                                    <span>Model</span>

                                    <strong>
                                        {{ $log->model }}
                                    </strong>

                                </div>

                                <div>

                                    <span>Status</span>

                                    <strong>
                                        {{ ucfirst($log->status) }}
                                    </strong>

                                </div>

                                <div>

                                    <span>Latency</span>

                                    <strong>
                                        {{ $log->latency_ms }} ms
                                    </strong>

                                </div>

                            </div>

                            <div class="profile-side-divider"></div>

                            <div class="log-payload-section">

                                <h3>
                                    Request Payload
                                </h3>

                                <pre>{{ json_encode($log->request_payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>

                            </div>

                            <div class="log-payload-section">

                                <h3>
                                    Response Payload
                                </h3>

                                <pre>{{ json_encode($log->response_payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>

                            </div>

                            @if ($log->error_message)

                                <div class="log-error-box">

                                    <h3>
                                        Error
                                    </h3>

                                    <p>
                                        {{ $log->error_message }}
                                    </p>

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

                        <td colspan="7">
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

@endsection