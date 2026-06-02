<?php

namespace App\Services\AI;

use App\Models\LlmLog;

class LlmLogService
{
    public function create(
        array $data
    ): LlmLog {

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
                $data['model'] ?? null,

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
                $data['input_tokens'] ?? 0,

            'output_tokens' =>
                $data['output_tokens'] ?? 0,

            'latency_ms' =>
                $data['latency_ms'] ?? 0,

            'status' =>
                $data['status'] ?? 'success',

            'error_message' =>
                $data['error_message'] ?? null,
        ]);
    }

    protected function toJsonIfNeeded(
        mixed $value
    ): mixed {

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