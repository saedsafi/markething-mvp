<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
        
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
        
            $table->enum('role', ['founder', 'agency'])->default('agency');
            $table->enum('status', ['inactive', 'active', 'suspended'])->default('inactive');
        
            $table->boolean('must_change_password')->default(true);
            $table->unsignedInteger('client_limit')->default(10);
            $table->unsignedInteger('daily_ai_assist_limit')->default(50);
        
            $table->timestamp('password_changed_at')->nullable();
            $table->timestamp('last_login_at')->nullable();
        
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
