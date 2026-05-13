<?php

namespace App\Services;

class FakeAiAssistService
{
    public function generate(array $data): array
    {
        $field = $data['field'];

        $client =
            $data['client_name'] ?? 'the business';

        $industry =
            $data['industry'] ?? 'modern business';

        $responses = [

            'business_offer' => [
                "{$client} offers premium {$industry} experiences focused on quality, trust, and customer satisfaction.",
                "A customer-focused {$industry} brand delivering innovative solutions and memorable experiences.",
            ],

            'brand_personality' => [
                "{$client} communicates with a modern, confident, and approachable tone while maintaining a premium feel.",
                "The brand personality is creative, energetic, authentic, and socially engaging.",
            ],

            'persona_description' => [
                "Young professionals looking for convenience, quality, and trend-aware experiences.",
                "Digitally active customers who value authenticity, aesthetics, and premium service.",
            ],

            'business_context' => [
                "{$client} is a growing {$industry} business focused on customer engagement, online visibility, and long-term brand loyalty.",
                "This business aims to strengthen its digital presence while building a loyal and socially active audience.",
            ],
        ];

        $pool =
            $responses[$field]
            ?? [
                "AI-generated assistance content for {$field}.",
            ];

        return [

            'text' => collect($pool)->random(),

            'tokens' => rand(200, 1200),

            'latency_ms' => rand(700, 2400),

            'status' => 'success',
        ];
    }
}