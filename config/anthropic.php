<?php

return [
    'api_key' => env('ANTHROPIC_API_KEY'),

    'model' => env('ANTHROPIC_MODEL', 'claude-sonnet-4-5'),

    'max_tokens' => (int) env('ANTHROPIC_MAX_TOKENS', 6000),

    'version' => env('ANTHROPIC_VERSION', '2023-06-01'),

    'base_url' => env('ANTHROPIC_BASE_URL', 'https://api.anthropic.com/v1/messages'),
];