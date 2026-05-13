<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Campaign extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'client_id',
        'persona_id',
        'name',
        'objective',
        'start_date',
        'end_date',
        'channels',
        'requested_posts_count',
        'description',
        'status',
        'snapshot',
        'prompt_version_id',
    ];

    protected $casts = [
        'channels' => 'array',
        'snapshot' => 'array',
        'start_date' => 'date',
        'end_date' => 'date',
        'requested_posts_count' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function persona(): BelongsTo
    {
        return $this->belongsTo(Persona::class);
    }

    public function promptVersion(): BelongsTo
    {
        return $this->belongsTo(PromptVersion::class);
    }

    public function posts(): HasMany
    {
        return $this->hasMany(CampaignPost::class);
    }

    public function llmLogs(): HasMany
    {
        return $this->hasMany(LlmLog::class);
    }

    public function isGenerating(): bool
    {
        return $this->status === 'generating';
    }

    public function isGenerated(): bool
    {
        return $this->status === 'generated';
    }

    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }
}