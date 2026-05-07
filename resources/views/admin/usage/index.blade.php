@extends('layouts.dashboard')

@section('title', 'Usage & Cost - MARKETHING')

@section('page-title', 'Usage & Cost Dashboard')
@section('page-subtitle', 'Track AI usage, token consumption, estimated costs, and per-user breakdown.')

@section('user-name', 'Founder Admin')
@section('user-role', 'Platform Owner')

@section('dashboard-content')

<div class="admin-usage-page">

    <div class="stats-grid">

        <x-stats-card label="Monthly Tokens" value="128K" hint="Across all AI calls" />
        <x-stats-card label="Estimated Cost" value="$37.40" hint="Current month" />
        <x-stats-card label="Assist Calls" value="842" hint="This month" />

    </div>

    <div class="usage-dashboard-grid">

        <div class="table-card">
            <h2 class="section-title">Cost By Call Type</h2>

            <div class="cost-chart-demo">

                <div class="cost-bar-row">
                    <span>Campaign Generation</span>
                    <div><b style="width:72%;"></b></div>
                    <strong>$24.10</strong>
                </div>

                <div class="cost-bar-row">
                    <span>Post Regeneration</span>
                    <div><b style="width:32%;"></b></div>
                    <strong>$6.20</strong>
                </div>

                <div class="cost-bar-row">
                    <span>AI Assist</span>
                    <div><b style="width:46%;"></b></div>
                    <strong>$7.10</strong>
                </div>

            </div>
        </div>

        <div class="table-card">
            <h2 class="section-title">Top Users By Cost</h2>

            <div class="mini-campaign-list">
                <div>
                    <strong>nova@example.com</strong>
                    <span>$12.80 · 42K tokens</span>
                </div>

                <div>
                    <strong>bluepeak@example.com</strong>
                    <span>$9.30 · 31K tokens</span>
                </div>

                <div>
                    <strong>pixel@example.com</strong>
                    <span>$6.70 · 22K tokens</span>
                </div>
            </div>
        </div>

    </div>

    <x-data-table title="Per-User Usage">

        <table class="dashboard-table">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Campaign Tokens</th>
                    <th>Assist Tokens</th>
                    <th>Regeneration Tokens</th>
                    <th>Total Cost</th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td>nova@example.com</td>
                    <td>28K</td>
                    <td>8K</td>
                    <td>6K</td>
                    <td>$12.80</td>
                </tr>

                <tr>
                    <td>bluepeak@example.com</td>
                    <td>21K</td>
                    <td>6K</td>
                    <td>4K</td>
                    <td>$9.30</td>
                </tr>

                <tr>
                    <td>pixel@example.com</td>
                    <td>14K</td>
                    <td>5K</td>
                    <td>3K</td>
                    <td>$6.70</td>
                </tr>
            </tbody>
        </table>

    </x-data-table>

</div>

@endsection