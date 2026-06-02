<?php

namespace App\Services\AI;

class PromptCompilerService
{
    public function compile(
        string $template,
        array $variables = []
    ): string {

        $compiled = $template;

        foreach ($variables as $key => $value) {

            if (
                is_array($value) ||
                is_object($value)
            ) {

                $value = json_encode(
                    $value,
                    JSON_PRETTY_PRINT |
                    JSON_UNESCAPED_UNICODE
                );
            }

            $compiled = str_replace(
                '{{' . $key . '}}',
                trim((string) $value),
                $compiled
            );
        }

        return $this->applyGlobalRules(
            $compiled
        );
    }

    protected function applyGlobalRules(
        string $prompt
    ): string {

        return trim($prompt) . "

IMPORTANT RULES:
- All generated outputs MUST be written in professional Standard Arabic.
- Do NOT use slang or dialect.
- Keep formatting clean and readable.
- Avoid repetition.
- Output should feel premium and marketing-focused.
";
    }
}