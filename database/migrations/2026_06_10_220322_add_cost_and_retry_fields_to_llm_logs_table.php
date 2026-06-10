<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('llm_logs', function (Blueprint $table) {
            $table
                ->decimal('estimated_cost_usd', 10, 6)
                ->default(0)
                ->after('output_tokens');

            $table
                ->unsignedInteger('retry_count')
                ->default(0)
                ->after('estimated_cost_usd');
        });
    }

    public function down(): void
    {
        Schema::table('llm_logs', function (Blueprint $table) {
            $table->dropColumn([
                'estimated_cost_usd',
                'retry_count',
            ]);
        });
    }
};