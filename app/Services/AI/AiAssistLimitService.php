<?php

namespace App\Services\AI;

use App\Models\AppSetting;
use App\Models\LlmLog;
use App\Models\User;

class AiAssistLimitService
{
    public function dailyLimit(): int
    {
        return (int) AppSetting::query()
            ->where('key', 'ai_assist_daily_limit')
            ->value('value') ?: 50;
    }

    public function usedToday(User $user): int
    {
        return LlmLog::query()
            ->where('user_id', $user->id)
            ->where('call_type', 'ai_assist')
            ->where('status', 'success')
            ->whereBetween('created_at', [
                now()->startOfDay()->format('Y-m-d H:i:s'),
                now()->endOfDay()->format('Y-m-d H:i:s'),
            ])
            ->count();
    }

    public function remainingToday(User $user): int
    {
        return max(
            0,
            $this->dailyLimit() - $this->usedToday($user)
        );
    }

    public function hasReachedDailyLimit(User $user): bool
    {
        return $this->remainingToday($user) <= 0;
    }
}