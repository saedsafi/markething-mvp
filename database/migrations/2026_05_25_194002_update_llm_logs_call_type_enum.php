<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE llm_logs
            MODIFY call_type ENUM(
                'campaign_generation',
                'post_regeneration',
                'ai_assist',
                'prompt_test'
            ) NOT NULL
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE llm_logs
            MODIFY call_type ENUM(
                'campaign_generation',
                'post_regeneration',
                'prompt_test'
            ) NOT NULL
        ");
    }
};