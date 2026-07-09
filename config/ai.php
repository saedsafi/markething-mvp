<?php

return [

    'anthropic' => [

        'api_key' => env('ANTHROPIC_API_KEY'),

        'model' => env(
            'CLAUDE_MODEL',
            'claude-opus-4-7'
        ),

        'endpoint' => env(
            'ANTHROPIC_ENDPOINT',
            'https://api.anthropic.com/v1/messages'
        ),

        'version' => env(
            'ANTHROPIC_VERSION',
            '2023-06-01'
        ),

        'max_tokens' => (int) env('ANTHROPIC_MAX_TOKENS', 100000),

    ],

];