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

        return $decoded;
    }

    protected function extractJson(
        string $response
    ): string {
    
        $response = trim($response);
    
        /*
        |--------------------------------------------------------------------------
        | Remove markdown wrappers
        |--------------------------------------------------------------------------
        */
    
        $response = preg_replace(
            '/```json|```/',
            '',
            $response
        );
    
        /*
        |--------------------------------------------------------------------------
        | Remove triple quotes
        |--------------------------------------------------------------------------
        */
    
        $response = trim(
            $response,
            "\"' \n\r\t"
        );
    
        /*
        |--------------------------------------------------------------------------
        | Extract JSON array
        |--------------------------------------------------------------------------
        */
    
        preg_match(
            '/\[.*\]/s',
            $response,
            $matches
        );
    
        if (
            empty($matches[0])
        ) {
            throw new Exception(
                'No JSON array found in Claude response.'
            );
        }
    
        return $matches[0];
    }
}