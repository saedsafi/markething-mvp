@extends('layouts.dashboard')

@section('title', 'Usage & Cost - MARKETHING')

@section('page-title', 'Usage & Cost Dashboard')
@section('page-subtitle', 'Monitor AI usage, estimated spend, tokens, failures, retries, and assist usage.')

@section('user-name', auth()->user()->name ?? 'Founder Admin')
@section('user-role', 'Platform Owner')

@section('dashboard-content')

<div class="admin-page">

    <div class="stats-grid">
        <x-stats-card label="All-Time Cost" value="${{ number_format($totalCost, 6) }}" hint="Estimated total AI cost" />
        <x-stats-card label="Current Month Cost" value="${{ number_format($monthCost, 6) }}" hint="{{ number_format($monthTokens) }} tokens this month" />
        <x-stats-card label="Total Tokens" value="{{ number_format($totalTokens) }}" hint="Input: {{ number_format($totalInputTokens) }} / Output: {{ number_format($totalOutputTokens) }}" />
        <x-stats-card label="AI Calls" value="{{ number_format($totalCalls) }}" hint="{{ $successfulCalls }} successful / {{ $failedCalls }} failed" />
    </div>

    <div class="stats-grid">
        <x-stats-card label="Retries" value="{{ number_format($totalRetries) }}" hint="Total retry attempts logged" />
        <x-stats-card label="Assist Calls Today" value="{{ number_format($todayAssistCalls) }}" hint="Per-user cap depends on settings" />
        <x-stats-card label="Assist Calls This Month" value="{{ number_format($monthAssistCalls) }}" hint="Monthly assist usage" />
        <x-stats-card label="Calls This Month" value="{{ number_format($monthCalls) }}" hint="All AI call types" />
    </div>

    <div class="admin-main-stack">

        <x-data-table title="Cost by Call Type">
            <table class="dashboard-table">
                <thead>
                    <tr>
                        <th>Call Type</th>
                        <th>Calls</th>
                        <th>Tokens</th>
                        <th>Failures</th>
                        <th>Retries</th>
                        <th>Estimated Cost</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($costByCallType as $row)
                        <tr>
                            <td><strong>{{ str_replace('_', ' ', ucfirst($row['call_type'])) }}</strong></td>
                            <td>{{ number_format($row['calls']) }}</td>
                            <td>{{ number_format($row['tokens']) }}</td>
                            <td>{{ number_format($row['failures']) }}</td>
                            <td>{{ number_format($row['retries']) }}</td>
                            <td><strong>${{ number_format($row['cost'], 6) }}</strong></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">No usage data yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </x-data-table>

        <x-data-table title="Cost & Assist Usage by Agency">
            <table class="dashboard-table">
                <thead>
                    <tr>
                        <th>Agency</th>
                        <th>Calls</th>
                        <th>Tokens</th>
                        <th>Assist Today</th>
                        <th>Assist Month</th>
                        <th>Failures</th>
                        <th>Estimated Cost</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($costByAgency as $row)
                        <tr>
                            <td>
                                <strong>{{ $row['agency'] }}</strong>
                                <p class="table-muted">{{ $row['email'] }}</p>
                            </td>

                            <td>{{ number_format($row['calls']) }}</td>
                            <td>{{ number_format($row['tokens']) }}</td>
                            <td>{{ number_format($row['assist_today']) }}</td>
                            <td>{{ number_format($row['assist_month']) }}</td>
                            <td>{{ number_format($row['failures']) }}</td>
                            <td><strong>${{ number_format($row['cost'], 6) }}</strong></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">No agency usage yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </x-data-table>

        <x-data-table title="Top Campaigns by Cost">
            <table class="dashboard-table">
                <thead>
                    <tr>
                        <th>Campaign</th>
                        <th>Agency</th>
                        <th>Calls</th>
                        <th>Tokens</th>
                        <th>Failures</th>
                        <th>Retries</th>
                        <th>Estimated Cost</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($costByCampaign as $row)
                        <tr>
                            <td><strong>{{ $row['campaign'] }}</strong></td>
                            <td>{{ $row['agency'] }}</td>
                            <td>{{ number_format($row['calls']) }}</td>
                            <td>{{ number_format($row['tokens']) }}</td>
                            <td>{{ number_format($row['failures']) }}</td>
                            <td>{{ number_format($row['retries']) }}</td>
                            <td><strong>${{ number_format($row['cost'], 6) }}</strong></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">No campaign usage yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </x-data-table>

        <x-data-table title="Most Expensive Recent Calls">
            <table class="dashboard-table">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>User</th>
                        <th>Campaign</th>
                        <th>Tokens</th>
                        <th>Cost</th>
                        <th>Retries</th>
                        <th>Status</th>
                        <th>Created</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($recentExpensiveCalls as $log)
                        <tr>
                            <td><strong>{{ str_replace('_', ' ', ucfirst($log->call_type)) }}</strong></td>
                            <td>{{ $log->user?->email ?? 'System' }}</td>
                            <td>{{ $log->campaign?->name ?? '—' }}</td>
                            <td>{{ number_format(($log->input_tokens ?? 0) + ($log->output_tokens ?? 0)) }}</td>
                            <td><strong>${{ number_format((float) ($log->estimated_cost_usd ?? 0), 6) }}</strong></td>
                            <td>{{ number_format($log->retry_count ?? 0) }}</td>

                            <td>
                                @if ($log->status === 'success')
                                    <span class="status active-status">Success</span>
                                @else
                                    <span class="status suspended-status">Failed</span>
                                @endif
                            </td>

                            <td>{{ $log->created_at->format('d M Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">No expensive calls yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </x-data-table>

    </div>

</div>

@endsection