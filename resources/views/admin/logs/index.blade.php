@extends('layouts.dashboard')

@section('title', 'LLM Logs - MARKETHING')

@section('page-title', 'LLM Call Logs')
@section('page-subtitle', 'Inspect AI calls, prompts, responses, latency, costs, and failures.')

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
                <option>Post Regeneration</option>
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
            <input class="form-input" type="text" placeholder="agency@example.com">
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
                    <th>Cost</th>
                    <th>Status</th>
                    <th>Details</th>
                </tr>
            </thead>

            <tbody>
                <x-log-row
                    type="Campaign Generation"
                    time="May 7, 2026 · 14:22"
                    user="nova@example.com"
                    model="Claude"
                    tokens="12,440"
                    cost="$2.18"
                    status="Success"
                    statusClass="active-status"
                />

                <x-log-row
                    type="AI Assist"
                    time="May 7, 2026 · 13:10"
                    user="bluepeak@example.com"
                    model="Claude"
                    tokens="820"
                    cost="$0.08"
                    status="Success"
                    statusClass="active-status"
                />

                <x-log-row
                    type="Post Regeneration"
                    time="May 7, 2026 · 11:42"
                    user="pixel@example.com"
                    model="Claude"
                    tokens="2,100"
                    cost="$0.31"
                    status="Failed"
                    statusClass="suspended-status"
                />
            </tbody>
        </table>

    </x-data-table>

</div>

<x-modal id="logDetailModal" title="LLM Call Detail" subtitle="Prompt and response inspection demo.">
    <div class="log-detail-box">
        <h3>Assembled Prompt</h3>
        <pre>{{business_context}} + {{campaign_objective}} + {{selected_persona}}</pre>

        <h3>LLM Response</h3>
        <pre>{ "posts": [ "Generated campaign post..." ] }</pre>

        <h3>Metadata</h3>
        <p>Latency: 8.4s · Retries: 0 · Prompt Version: Master v1.0</p>
    </div>
</x-modal>

@endsection