<?php

namespace App\Services\AI;

use App\Models\LlmLog;

class LlmLogService
{
    public function create(array $data): LlmLog
    {
        $inputTokens =
            (int) ($data['input_tokens'] ?? 0);

        $outputTokens =
            (int) ($data['output_tokens'] ?? 0);

        $model =
            $data['model']
            ?? config('ai.anthropic.model');

        $estimatedCostUsd =
            $data['estimated_cost_usd']
            ?? $this->estimateCost(
                $model,
                $inputTokens,
                $outputTokens
            );

        return LlmLog::create([

            'user_id' =>
                $data['user_id'] ?? null,

            'client_id' =>
                $data['client_id'] ?? null,

            'campaign_id' =>
                $data['campaign_id'] ?? null,

            'campaign_post_id' =>
                $data['campaign_post_id'] ?? null,

            'question_key' =>
                $data['question_key'] ?? null,

            'call_type' =>
                $data['call_type'] ?? null,

            'provider' =>
                $data['provider'] ?? null,

            'model' =>
                $model,

            'prompt_version_id' =>
                $data['prompt_version_id'] ?? null,

            'assembled_prompt' =>
                $this->toJsonIfNeeded(
                    $data['assembled_prompt'] ?? null
                ),

            'response' =>
                $this->toJsonIfNeeded(
                    $data['response'] ?? null
                ),

            'input_tokens' =>
                $inputTokens,

            'output_tokens' =>
                $outputTokens,

            'estimated_cost_usd' =>
                $estimatedCostUsd,

            'retry_count' =>
                (int) ($data['retry_count'] ?? 0),

            'latency_ms' =>
                (int) ($data['latency_ms'] ?? 0),

            'status' =>
                $data['status'] ?? 'success',

            'error_message' =>
                $data['error_message'] ?? null,
        ]);
    }

    protected function estimateCost(
        ?string $model,
        int $inputTokens,
        int $outputTokens
    ): float {

        /*
        |--------------------------------------------------------------------------
        | Estimated Anthropic Pricing
        |--------------------------------------------------------------------------
        | Update these numbers if the provider/model pricing changes.
        |
        | Formula:
        | input cost  = input tokens / 1,000,000 × input price
        | output cost = output tokens / 1,000,000 × output price
        |--------------------------------------------------------------------------
        */

        $pricing = match ($model) {

            'claude-3-5-sonnet-20241022',
            'claude-3-5-sonnet-latest',
            'claude-sonnet-4-5',
            'claude-sonnet-4-5-20250929' => [
                'input' => 3.00,
                'output' => 15.00,
            ],

            'claude-3-opus-20240229',
            'claude-opus-4-7',
            'claude-opus-4-7-latest' => [
                'input' => 15.00,
                'output' => 75.00,
            ],

            'claude-3-haiku-20240307',
            'claude-3-5-haiku-latest' => [
                'input' => 0.25,
                'output' => 1.25,
            ],

            default => [
                'input' => 3.00,
                'output' => 15.00,
            ],
        };

        $inputCost =
            ($inputTokens / 1_000_000)
            * $pricing['input'];

        $outputCost =
            ($outputTokens / 1_000_000)
            * $pricing['output'];

        return round(
            $inputCost + $outputCost,
            6
        );
    }

    protected function toJsonIfNeeded(mixed $value): mixed
    {
        if (
            is_array($value) ||
            is_object($value)
        ) {
            return json_encode(
                $value,
                JSON_PRETTY_PRINT |
                JSON_UNESCAPED_UNICODE
            );
        }

        return $value;
    }
}