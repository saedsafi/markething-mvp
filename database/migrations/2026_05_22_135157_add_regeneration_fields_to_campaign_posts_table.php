<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table(
            'campaign_posts',
            function (Blueprint $table) {

                $table->boolean('is_regenerated')
                    ->default(false);

                $table->unsignedInteger(
                    'regeneration_count'
                )->default(0);
            }
        );
    }

    public function down(): void
    {
        Schema::table(
            'campaign_posts',
            function (Blueprint $table) {

                $table->dropColumn([
                    'is_regenerated',
                    'regeneration_count',
                ]);
            }
        );
    }
};