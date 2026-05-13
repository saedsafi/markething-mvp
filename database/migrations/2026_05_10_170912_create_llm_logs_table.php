<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('llm_logs', function (Blueprint $table) {
            $table->id();

            $table->enum('call_type', [
                'campaign_generation',
                'assist_call'
            ]);

            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('client_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('campaign_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('campaign_post_id')
                ->nullable()
                ->constrained('campaign_posts')
                ->nullOnDelete();

            $table->string('question_key')->nullable();

            $table->string('provider')->default('anthropic');
            $table->string('model')->nullable();

            $table->foreignId('prompt_version_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->longText('assembled_prompt')->nullable();
            $table->longText('response')->nullable();

            $table->unsignedInteger('input_tokens')->nullable();
            $table->unsignedInteger('output_tokens')->nullable();

            $table->unsignedInteger('latency_ms')->nullable();

            $table->enum('status', ['success', 'failed'])->default('success');

            $table->text('error_message')->nullable();

            $table->timestamps();

            $table->index(['user_id', 'call_type']);
            $table->index(['status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('llm_logs');
    }
};