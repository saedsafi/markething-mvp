<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
        'must_change_password',
        'client_limit',
        'daily_ai_assist_limit',
        'password_changed_at',
        'last_login_at',
        'tier',
        'uses_test_prompts',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
        'must_change_password' => 'boolean',
        'client_limit' => 'integer',
        'daily_ai_assist_limit' => 'integer',
        'password_changed_at' => 'datetime',
        'last_login_at' => 'datetime',
        'uses_test_prompts' => 'boolean',
    ];

    public function clients(): HasMany
    {
        return $this->hasMany(Client::class);
    }

    public function campaigns(): HasMany
    {
        return $this->hasMany(Campaign::class);
    }

    public function llmLogs(): HasMany
    {
        return $this->hasMany(LlmLog::class);
    }

    public function createdPromptVersions(): HasMany
    {
        return $this->hasMany(PromptVersion::class, 'created_by');
    }
    

    public function isFounder(): bool
    {
        return $this->role === 'founder';
    }

    public function isAgency(): bool
    {
        return $this->role === 'agency';
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isSuspended(): bool
    {
        return $this->status === 'suspended';
    }

    public function isInactive(): bool
    {
        return $this->status === 'inactive';
    }
}