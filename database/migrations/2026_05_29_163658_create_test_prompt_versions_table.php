<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('test_prompt_versions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('prompt_template_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('version');
            $table->longText('content');

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->boolean('is_active')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('test_prompt_versions');
    }
};