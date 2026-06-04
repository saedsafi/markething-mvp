<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use App\Http\Requests\Agency\UpdateSettingsRequest;
use App\Services\AI\AiAssistLimitService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function index(
        AiAssistLimitService $aiAssistLimitService
    ): View {
        $user = auth()->user();
    
        return view('agency.settings.index', [
            'aiAssistUsedToday' => $aiAssistLimitService->usedToday($user),
            'aiAssistDailyLimit' => $aiAssistLimitService->dailyLimit(),
        ]);
    }

    public function update(
        UpdateSettingsRequest $request
    ): RedirectResponse {
        $request->user()->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return back()->with(
            'success',
            'Settings updated successfully.'
        );
    }
}