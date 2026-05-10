@extends('layouts.dashboard')

@section('title', 'LLM Logs - MARKETHING')

@section('page-title', 'LLM Call Logs')
@section('page-subtitle', 'Inspect AI calls, prompts, responses, latency, and failures.')

@section('user-name', 'Founder Admin')
@section('user-role', 'Platform Owner')

@section('dashboard-content')

<div class="admin-logs-page">

    <div class="filters-card">

        <div class="form-group">
            <label class="form-label">Call Type</label>

            <select class="form-input">
                <option>All</option>
                <option>Campaign Generation</option>
                <option>Assist Call</option>
            </select>
        </div>

        <div class="form-group">
            <label class="form-label">Status</label>

            <select class="form-input">
                <option>All</option>
                <option>Success</option>
                <option>Failed</option>
            </select>
        </div>

        <div class="form-group">
            <label class="form-label">Search User</label>

            <input
                class="form-input"
                type="text"
                placeholder="agency@example.com"
            >
        </div>

        <button class="btn btn-primary" type="button">
            Apply Filters
        </button>

    </div>

    <x-data-table title="Recent LLM Calls">

        <table class="dashboard-table">

            <thead>
                <tr>
                    <th>Call</th>
                    <th>User</th>
                    <th>Model</th>
                    <th>Tokens</th>
                    <th>Status</th>
                    <th>Details</th>
                </tr>
            </thead>

            <tbody>

                <tr>
                    <td>
                        <strong>Campaign Generation</strong>
                        <p class="table-muted">May 7, 2026 · 14:22</p>
                    </td>

                    <td>nova@example.com</td>

                    <td>Claude</td>

                    <td>12,440</td>

                    <td>
                        <span class="status active-status">Success</span>
                    </td>

                    <td>
                        <button class="mini-btn" type="button" data-open-log>
                            View
                        </button>
                    </td>
                </tr>

                <tr>
                    <td>
                        <strong>AI Assist</strong>
                        <p class="table-muted">May 7, 2026 · 13:10</p>
                    </td>

                    <td>bluepeak@example.com</td>

                    <td>Claude</td>

                    <td>820</td>

                    <td>
                        <span class="status active-status">Success</span>
                    </td>

                    <td>
                        <button class="mini-btn" type="button" data-open-log>
                            View
                        </button>
                    </td>
                </tr>

                <tr>
                    <td>
                        <strong>Campaign Generation</strong>
                        <p class="table-muted">May 7, 2026 · 11:42</p>
                    </td>

                    <td>pixel@example.com</td>

                    <td>Claude</td>

                    <td>2,100</td>

                    <td>
                        <span class="status suspended-status">Failed</span>
                    </td>

                    <td>
                        <button class="mini-btn" type="button" data-open-log>
                            View
                        </button>
                    </td>
                </tr>

            </tbody>

        </table>

    </x-data-table>

</div>

<x-modal
    id="logDetailModal"
    title="LLM Call Detail"
    subtitle="Prompt, response, and metadata inspection."
>

    <div class="log-detail-box">

        <h3>Assembled Prompt</h3>

        <pre>@verbatim{{business_context}} + {{campaign_objective}} + {{selected_persona}}@endverbatim</pre>

        <h3>LLM Response</h3>

        <pre>{
  "posts": [
    "Generated campaign post..."
  ]
}</pre>

        <h3>Metadata</h3>

        <p>
            Latency: 8.4s · Prompt Version: Master v1.0 · Status: Success
        </p>

        <h3>Error Details</h3>

        <p class="profile-text">
            If the call fails or returns invalid JSON, the user-facing message is:
            “network error, please try again later”.
        </p>

    </div>

</x-modal>

@endsection