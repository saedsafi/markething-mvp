<?php

namespace Database\Seeders;

use App\Models\AppSetting;
use Illuminate\Database\Seeder;

class AppSettingSeeder extends Seeder
{
    public function run(): void
    {
        AppSetting::updateOrCreate(
            ['key' => 'max_campaign_days'],
            [
                'value' => 90,
                'description' => 'Maximum campaign date range in days.',
            ]
        );
    }
}