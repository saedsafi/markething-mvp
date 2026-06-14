<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LlmLog;
use Carbon\Carbon;
use Illuminate\View\View;

class UsageController extends Controller
{
    public function index(): View
    {
        $logs = LlmLog::query()
            ->with([
                'user',
                'campaign.user',
            ])
            ->latest()
            ->get();

        $monthStart = now()->startOfMonth();
        $monthEnd = now()->endOfMonth();
        $todayStart = now()->startOfDay();
        $todayEnd = now()->endOfDay();

        $monthLogs = $logs->filter(function ($log) use ($monthStart, $monthEnd) {
            return $log->created_at->between($monthStart, $monthEnd);
        });

        $todayLogs = $logs->filter(function ($log) use ($todayStart, $todayEnd) {
            return $log->created_at->between($todayStart, $todayEnd);
        });

        $totalInputTokens = $logs->sum('input_tokens');
        $totalOutputTokens = $logs->sum('output_tokens');

        $monthInputTokens = $monthLogs->sum('input_tokens');
        $monthOutputTokens = $monthLogs->sum('output_tokens');

        $costByCallType = $logs
            ->groupBy('call_type')
            ->map(function ($items, $callType) {
                return [
                    'call_type' => $callType,
                    'calls' => $items->count(),
                    'tokens' => $items->sum('input_tokens') + $items->sum('output_tokens'),
                    'cost' => $items->sum('estimated_cost_usd'),
                    'failures' => $items->where('status', 'failed')->count(),
                    'retries' => $items->sum('retry_count'),
                ];
            })
            ->sortByDesc('cost')
            ->values();

        $costByAgency = $logs
            ->whereNotNull('user_id')
            ->groupBy('user_id')
            ->map(function ($items) {
                $user = $items->first()->user;

                return [
                    'agency' => $user?->name ?? 'Deleted Agency',
                    'email' => $user?->email ?? '—',
                    'calls' => $items->count(),
                    'tokens' => $items->sum('input_tokens') + $items->sum('output_tokens'),
                    'cost' => $items->sum('estimated_cost_usd'),
                    'failures' => $items->where('status', 'failed')->count(),
                    'assist_today' => $items
                        ->where('call_type', 'ai_assist')
                        ->filter(fn ($log) => $log->created_at->isToday())
                        ->count(),
                    'assist_month' => $items
                        ->where('call_type', 'ai_assist')
                        ->filter(fn ($log) => $log->created_at->isSameMonth(now()))
                        ->count(),
                ];
            })
            ->sortByDesc('cost')
            ->values();

        $costByCampaign = $logs
            ->whereNotNull('campaign_id')
            ->groupBy('campaign_id')
            ->map(function ($items) {
                $campaign = $items->first()->campaign;

                return [
                    'campaign' => $campaign?->name ?? 'Deleted Campaign',
                    'agency' => $campaign?->user?->name ?? 'Deleted Agency',
                    'calls' => $items->count(),
                    'tokens' => $items->sum('input_tokens') + $items->sum('output_tokens'),
                    'cost' => $items->sum('estimated_cost_usd'),
                    'failures' => $items->where('status', 'failed')->count(),
                    'retries' => $items->sum('retry_count'),
                ];
            })
            ->sortByDesc('cost')
            ->take(10)
            ->values();

        $recentExpensiveCalls = $logs
            ->sortByDesc('estimated_cost_usd')
            ->take(10)
            ->values();

        return view('admin.usage.index', [
            'totalCalls' => $logs->count(),
            'successfulCalls' => $logs->where('status', 'success')->count(),
            'failedCalls' => $logs->where('status', 'failed')->count(),

            'monthCalls' => $monthLogs->count(),
            'monthCost' => $monthLogs->sum('estimated_cost_usd'),
            'monthTokens' => $monthInputTokens + $monthOutputTokens,

            'todayAssistCalls' => $todayLogs->where('call_type', 'ai_assist')->count(),
            'monthAssistCalls' => $monthLogs->where('call_type', 'ai_assist')->count(),

            'totalInputTokens' => $totalInputTokens,
            'totalOutputTokens' => $totalOutputTokens,
            'totalTokens' => $totalInputTokens + $totalOutputTokens,
            'totalCost' => $logs->sum('estimated_cost_usd'),
            'totalRetries' => $logs->sum('retry_count'),

            'costByCallType' => $costByCallType,
            'costByAgency' => $costByAgency,
            'costByCampaign' => $costByCampaign,
            'recentExpensiveCalls' => $recentExpensiveCalls,
        ]);
    }
}