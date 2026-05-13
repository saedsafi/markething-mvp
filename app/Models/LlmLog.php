<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LlmLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'call_type',
        'user_id',
        'client_id',
        'campaign_id',
        'campaign_post_id',
        'question_key',
        'provider',
        'model',
        'prompt_version_id',
        'assembled_prompt',
        'response',
        'input_tokens',
        'output_tokens',
        'latency_ms',
        'status',
        'error_message',
    ];

    protected $casts = [
        'input_tokens' => 'integer',
        'output_tokens' => 'integer',
        'latency_ms' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    public function campaignPost(): BelongsTo
    {
        return $this->belongsTo(CampaignPost::class);
    }

    public function promptVersion(): BelongsTo
    {
        return $this->belongsTo(PromptVersion::class);
    }

    public function isSuccess(): bool
    {
        return $this->status === 'success';
    }

    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }
}