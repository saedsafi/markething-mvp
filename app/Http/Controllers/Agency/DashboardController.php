<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\LlmLog;
use App\Services\AppSettingService;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $user = $request->user();

        $clients = $user->clients()
            ->withCount(['personas', 'campaigns'])
            ->latest()
            ->take(5)
            ->get();

        $campaigns = $user->campaigns()
            ->latest()
            ->take(5)
            ->get();

        $activeClients = $user->clients()
            ->where('status', 'active')
            ->count();

        $inactiveClients = $user->clients()
            ->where('status', 'inactive')
            ->count();

        $totalPersonas = $user->clients()
            ->withCount('personas')
            ->get()
            ->sum('personas_count');

        $generatedCampaigns = $user->campaigns()
            ->where('status', 'generated')
            ->count();

        $failedCampaigns = $user->campaigns()
            ->where('status', 'failed')
            ->count();



        $aiAssistDailyLimit = app(\App\Services\AppSettingService::class)
            ->int('ai_assist_daily_limit', 50);
        
        $aiAssistUsedToday = \App\Models\LlmLog::query()
            ->where('user_id', auth()->id())
            ->where('call_type', 'ai_assist')
            ->whereDate('created_at', now()->toDateString())
            ->count();
        
        $aiAssistPercentage =
            $aiAssistDailyLimit > 0
                ? min(100, ($aiAssistUsedToday / $aiAssistDailyLimit) * 100)
                : 0;


        return view('agency.dashboard', [
            'clients' => $clients,
            'campaigns' => $campaigns,

            'activeClients' => $activeClients,
            'inactiveClients' => $inactiveClients,
            'totalPersonas' => $totalPersonas,

            'generatedCampaigns' => $generatedCampaigns,
            'failedCampaigns' => $failedCampaigns,

            'aiAssistDailyLimit' => $aiAssistDailyLimit,
            'aiAssistUsedToday' => $aiAssistUsedToday,
            'aiAssistPercentage' => $aiAssistPercentage,
        ]);
    }
}