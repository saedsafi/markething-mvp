<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'founder@markething.test'],
            [
                'name' => 'Founder Admin',
                'password' => 'Password123',
                'role' => 'founder',
                'status' => 'active',
                'must_change_password' => false,
                'client_limit' => 999,
                'daily_ai_assist_limit' => 999,
                'password_changed_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'agency@markething.test'],
            [
                'name' => 'Nova Marketing',
                'password' => 'Temp12345',
                'role' => 'agency',
                'status' => 'inactive',
                'must_change_password' => true,
                'client_limit' => 10,
                'daily_ai_assist_limit' => 50,
            ]
        );

        User::updateOrCreate(
            ['email' => 'active-agency@markething.test'],
            [
                'name' => 'Active Agency',
                'password' => 'Password123',
                'role' => 'agency',
                'status' => 'active',
                'must_change_password' => false,
                'client_limit' => 10,
                'daily_ai_assist_limit' => 50,
                'password_changed_at' => now(),
            ]
        );
    }
}