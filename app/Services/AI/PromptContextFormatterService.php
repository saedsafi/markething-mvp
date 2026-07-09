<?php

namespace App\Services\AI;

class PromptContextFormatterService
{
    public function businessInfo(array $info): string
    {
        $cities = $info['city'] ?? [];
        $cities = is_array($cities) ? $cities : [$cities];
    
        return collect([
    
            "Industry: " . ($info['industry'] ?? '-'),
    
            "Business Type: " . ($info['business_type'] ?? '-'),
    
            "Country: " . ($info['country'] ?? '-'),
    
            "City: " . implode(', ', $cities),
    
            "Price Tier: " . ($info['price_tier'] ?? '-'),
    
            "Business Age: " . ($info['business_age'] ?? '-'),
    
            "Business Differentiator:\n" . ($info['differentiator'] ?? '-'),
    
            "Brand Positioning:\n" .
            implode(', ', $info['brand_positioning'] ?? []),
    
            "Avoid:\n" .
            implode(', ', $info['brand_avoids'] ?? []),
    
        ])
        ->filter()
        ->implode("\n\n");
    }

    public function brandInfo(array $brand): string
    {
        $conversion = $brand['conversion'] ?? [];

        return collect([

            "Arabic Dialect: " .
            ($brand['arabic_dialect'] ?? '-'),

            "Emoji Usage: " .
            ($brand['emoji_usage'] ?? '-'),

            "English Usage: " .
            ($brand['english_usage'] ?? '-'),

            "Words To Avoid:\n" .
            ($brand['words_to_avoid'] ?? '-'),

            "Caption Samples:\n" .
            ($brand['caption_samples'] ?? '-'),

            "Available Conversion Methods:\n" .
            implode(', ', $brand['conversion_actions'] ?? []),

            "Conversion Details:\n" .
            collect($conversion)
                ->filter()
                ->map(fn($v, $k) => ucfirst($k) . ": {$v}")
                ->implode("\n"),

        ])
        ->filter()
        ->implode("\n\n");
    }

    public function persona(array $persona): string
    {
        return collect([

            "Gender: " .
            ($persona['gender'] ?? '-'),

            "Who They Are:\n" .
            ($persona['who'] ?? '-'),

            "Buyer Is User: " .
            ($persona['buyer_is_user'] ?? '-'),

            "Decision Maker:\n" .
            ($persona['decider'] ?? '-'),

            "Priorities:\n" .
            implode(', ', $persona['priorities'] ?? []),

            "Objections:\n" .
            ($persona['objection'] ?? '-'),

        ])
        ->filter()
        ->implode("\n\n");
    }
}