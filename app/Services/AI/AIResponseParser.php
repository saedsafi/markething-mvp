<?php

namespace App\Services\AI;

use Exception;

class AIResponseParser
{
    public function parseCampaignPosts(
        string $response
    ): array {

        $cleaned =
            $this->extractJson($response);

        $decoded =
            json_decode($cleaned, true);

        if (! is_array($decoded)) {
            throw new Exception(
                'Claude returned invalid JSON.'
            );
        }

        return array_values(
            array_filter(
                $decoded,
                fn ($post) => is_array($post)
            )
        );
    }

    protected function extractJson(
        string $response
    ): string {

        $response = trim($response);

        $response = preg_replace(
            '/```json|```/',
            '',
            $response
        );

        $response = trim(
            $response,
            "\"' \n\r\t"
        );

        preg_match(
            '/\[.*\]/s',
            $response,
            $matches
        );

        if (empty($matches[0])) {
            throw new Exception(
                'Claude response was incomplete or did not contain a complete JSON array. Try fewer posts or increase max_tokens.'
            );
        }

        return $matches[0];
    }
}