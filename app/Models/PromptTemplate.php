<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PromptTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'question_key',
        'description',
        'current_version_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function versions(): HasMany
    {
        return $this->hasMany(PromptVersion::class);
    }

    public function currentVersion(): BelongsTo
    {
        return $this->belongsTo(PromptVersion::class, 'current_version_id');
    }

    public function isMaster(): bool
    {
        return $this->type === 'master';
    }

    public function isAssist(): bool
    {
        return $this->type === 'assist';
    }
}