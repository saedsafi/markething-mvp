<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LlmLog;
use Illuminate\View\View;

class UsageController extends Controller
{
    public function index(): View
    {
        $logs = LlmLog::query()
            ->with([
                'user',
                'campaign',
            ])
            ->latest()
            ->get();

        $successfulLogs =
            $logs->where('status', 'success');

        $totalInputTokens =
            $logs->sum('input_tokens');

        $totalOutputTokens =
            $logs->sum('output_tokens');

        $totalCost =
            $logs->sum('estimated_cost_usd');

        $totalRetries =
            $logs->sum('retry_count');

        $failedCalls =
            $logs->where('status', 'failed')->count();

        $costByCallType =
            $logs
                ->groupBy('call_type')
                ->map(function ($items, $callType) {
                    return [
                        'call_type' => $callType,
                        'calls' => $items->count(),
                        'tokens' =>
                            $items->sum('input_tokens')
                            +
                            $items->sum('output_tokens'),
                        'cost' => $items->sum('estimated_cost_usd'),
                        'failures' => $items->where('status', 'failed')->count(),
                    ];
                })
                ->sortByDesc('cost')
                ->values();

        $costByAgency =
            $logs
                ->whereNotNull('user_id')
                ->groupBy('user_id')
                ->map(function ($items) {
                    $user = $items->first()->user;

                    return [
                        'agency' => $user?->name ?? 'Deleted Agency',
                        'email' => $user?->email ?? '—',
                        'calls' => $items->count(),
                        'tokens' =>
                            $items->sum('input_tokens')
                            +
                            $items->sum('output_tokens'),
                        'cost' => $items->sum('estimated_cost_usd'),
                        'failures' => $items->where('status', 'failed')->count(),
                    ];
                })
                ->sortByDesc('cost')
                ->values();

        $costByCampaign =
            $logs
                ->whereNotNull('campaign_id')
                ->groupBy('campaign_id')
                ->map(function ($items) {
                    $campaign = $items->first()->campaign;

                    return [
                        'campaign' => $campaign?->name ?? 'Deleted Campaign',
                        'agency' => $campaign?->user?->name ?? 'Deleted Agency',
                        'calls' => $items->count(),
                        'tokens' =>
                            $items->sum('input_tokens')
                            +
                            $items->sum('output_tokens'),
                        'cost' => $items->sum('estimated_cost_usd'),
                        'failures' => $items->where('status', 'failed')->count(),
                    ];
                })
                ->sortByDesc('cost')
                ->take(10)
                ->values();

        $recentExpensiveCalls =
            $logs
                ->sortByDesc('estimated_cost_usd')
                ->take(10)
                ->values();

        return view('admin.usage.index', [
            'totalCalls' => $logs->count(),
            'successfulCalls' => $successfulLogs->count(),
            'failedCalls' => $failedCalls,
            'totalInputTokens' => $totalInputTokens,
            'totalOutputTokens' => $totalOutputTokens,
            'totalTokens' => $totalInputTokens + $totalOutputTokens,
            'totalCost' => $totalCost,
            'totalRetries' => $totalRetries,
            'costByCallType' => $costByCallType,
            'costByAgency' => $costByAgency,
            'costByCampaign' => $costByCampaign,
            'recentExpensiveCalls' => $recentExpensiveCalls,
        ]);
    }
}