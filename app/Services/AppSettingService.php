<?php

namespace App\Services;

use App\Models\AppSetting;

class AppSettingService
{
    public function int(
        string $key,
        int $default
    ): int {
        return (int) (
            AppSetting::query()
                ->where('key', $key)
                ->value('value')
            ?? $default
        );
    }
}