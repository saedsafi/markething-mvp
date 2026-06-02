<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $table->string('tier')
                ->default('standard')
                ->after('role');

            $table->boolean('uses_test_prompts')
                ->default(false)
                ->after('tier');
        });

        DB::table('users')
            ->update([
                'tier' => 'standard',
                'uses_test_prompts' => false,
            ]);
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $table->dropColumn([
                'tier',
                'uses_test_prompts',
            ]);
        });
    }
};