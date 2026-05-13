<?php

namespace App\Services;

class FakePromptTestingService
{
    public function generate(string $prompt, string $input): array
    {
        return [

            'assembled_prompt' =>
                trim($prompt) . "\n\n" . trim($input),

            'response' =>
                "This is a simulated AI response generated from the current prompt version. The future Claude integration will replace this engine while keeping the same structure.",

            'tokens' => rand(400, 2400),

            'latency' => rand(2, 9) . 's',

            'status' => 'success',
        ];
    }
}