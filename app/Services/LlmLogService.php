<?php

namespace App\Services;

use App\Models\LlmLog;

class LlmLogService
{
    public function create(array $data): LlmLog
    {
        return LlmLog::create([

            'user_id' => $data['user_id'] ?? null,

            'campaign_id' => $data['campaign_id'] ?? null,

            'prompt_version_id' =>
                $data['prompt_version_id'] ?? null,

            'type' => $data['type'] ?? 'generation',

            'provider' => $data['provider'] ?? 'fake-ai',

            'model' => $data['model'] ?? 'simulation-engine',

            'request_payload' =>
                $data['request_payload'] ?? [],

            'response_payload' =>
                $data['response_payload'] ?? [],

            'tokens_input' =>
                $data['tokens_input'] ?? 0,

            'tokens_output' =>
                $data['tokens_output'] ?? 0,

            'latency_ms' =>
                $data['latency_ms'] ?? 0,

            'status' => $data['status'] ?? 'success',

            'error_message' =>
                $data['error_message'] ?? null,
        ]);
    }
}