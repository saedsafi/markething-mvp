<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('client_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('persona_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->string('name');
            $table->string('objective');

            $table->date('start_date');
            $table->date('end_date');

            $table->json('channels');
            $table->unsignedInteger('requested_posts_count');

            $table->longText('description')->nullable();

            $table->enum('status', ['generating', 'generated', 'failed'])->default('generating');

            $table->json('snapshot')->nullable();

            $table->foreignId('prompt_version_id')
                ->nullable()
                ->constrained('prompt_versions')
                ->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['user_id', 'status']);
            $table->index(['client_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campaigns');
    }
};