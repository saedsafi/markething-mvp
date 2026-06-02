<?php

namespace App\Services\AI;

class TextLimitService
{
    public function truncateAtWordBoundary(
        string $text,
        int $limit
    ): array {
        $text = trim($text);

        if (mb_strlen($text) <= $limit) {
            return [
                'text' => $text,
                'truncated' => false,
            ];
        }

        $cut = mb_substr($text, 0, $limit);

        $lastSpace = mb_strrpos($cut, ' ');

        if ($lastSpace !== false) {
            $cut = mb_substr($cut, 0, $lastSpace);
        }

        return [
            'text' => trim($cut),
            'truncated' => true,
        ];
    }
}