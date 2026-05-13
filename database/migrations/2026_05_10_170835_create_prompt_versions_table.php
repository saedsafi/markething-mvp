<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prompt_versions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('prompt_template_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('version');

            $table->longText('content');

            $table->longText('test_prompt')->nullable();

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            $table->index(['prompt_template_id', 'version']);
        });

        Schema::table('prompt_templates', function (Blueprint $table) {
            $table->foreign('current_version_id')
                ->references('id')
                ->on('prompt_versions')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('prompt_templates', function (Blueprint $table) {
            $table->dropForeign(['current_version_id']);
        });

        Schema::dropIfExists('prompt_versions');
    }
};