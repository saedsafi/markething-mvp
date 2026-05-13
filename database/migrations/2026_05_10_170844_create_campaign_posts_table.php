<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campaign_posts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('campaign_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->unsignedInteger('sequence_number');

            $table->date('scheduled_date');

            $table->string('channel');
            $table->string('media_type')->nullable();

            $table->text('summary')->nullable();
            $table->longText('caption')->nullable();
            $table->longText('hashtags')->nullable();
            $table->longText('creative_direction')->nullable();

            $table->boolean('is_edited')->default(false);

            $table->timestamps();

            $table->index(['campaign_id', 'scheduled_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campaign_posts');
    }
};
