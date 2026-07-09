<?php

namespace App\Services\AI;

use Exception;
use Illuminate\Support\Facades\Http;

class ClaudeService

{
    public function generate(
        string $prompt
    ): array {

        $config = config('ai.anthropic');

        $response = Http::timeout(100000)

            ->withHeaders([

                'x-api-key' =>
                    $config['api_key'],

                'anthropic-version' =>
                    $config['version'],

                'content-type' =>
                    'application/json',

            ])

            ->post(
                $config['endpoint'],
                [

                    'model' =>
                        $config['model'],

                    'max_tokens' =>
                        $config['max_tokens'],

                    'stop_sequences' => [
                        '</json>',
                    ],

                    'messages' => [

                        [
                            'role' => 'user',

                            'content' => $prompt,
                        ],

                    ],

                ]
            );

        if (! $response->successful()) {

            throw new Exception(
                'Claude API Error: ' .
                $response->body()
            );
        }

        $data = $response->json();

        return [

            'content' =>
                $data['content'][0]['text']
                ?? null,

            'input_tokens' =>
                $data['usage']['input_tokens']
                ?? 0,

            'output_tokens' =>
                $data['usage']['output_tokens']
                ?? 0,

            'raw' => $data,

        ];
    }
}