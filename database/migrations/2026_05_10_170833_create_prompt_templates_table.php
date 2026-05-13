<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prompt_templates', function (Blueprint $table) {
            $table->id();

            $table->string('name');

            $table->enum('type', [
                'master',
                'assist'
            ]);

            $table->string('question_key')->nullable();

            $table->text('description')->nullable();

            $table->foreignId('current_version_id')
                ->nullable();

            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->index(['type', 'question_key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prompt_templates');
    }
};