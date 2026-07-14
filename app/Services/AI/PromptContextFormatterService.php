<?php

namespace App\Services\AI;

class PromptContextFormatterService
{
    public function businessInfo(array $info): string
    {
        return $this->sections([
            'Industry' =>
                $info['industry'] ?? null,

            'Business Type' =>
                $info['business_type'] ?? null,

            'Other Business Type' =>
                $info['business_type_other'] ?? null,

            'Country' =>
                $info['country'] ?? null,

            'City / Service Area' =>
                $this->formatList($info['city'] ?? null),

            'Price Tier' =>
                $info['price_tier'] ?? null,

            'Business Age' =>
                $info['business_age'] ?? null,

            'Business Differentiator' =>
                $info['differentiator'] ?? null,

            'Brand Positioning' =>
                $this->formatList(
                    $info['brand_positioning'] ?? null
                ),

            'Brand Must Avoid' =>
                $this->combineValues([
                    $this->formatList(
                        $info['brand_avoids'] ?? null
                    ),
                    $info['brand_avoids_other'] ?? null,
                ]),
        ]);
    }

    public function brandInfo(array $brand): string
    {
        $conversionDetails =
            $this->formatKeyValueList(
                $brand['conversion'] ?? []
            );

        return $this->sections([
            /*
            |--------------------------------------------------------------------------
            | Put binding communication settings first
            |--------------------------------------------------------------------------
            */

            'REQUIRED Arabic Dialect / Variety' =>
                $brand['arabic_dialect'] ?? null,

            'REQUIRED Audience Language Rule' =>
                filled($brand['arabic_dialect'] ?? null)
                    ? 'Every caption must use the selected Arabic dialect or variety. '
                        . 'Reference material and caption samples must not override it.'
                    : null,

            'Emoji Usage' =>
                $brand['emoji_usage'] ?? null,

            'English Usage' =>
                $brand['english_usage'] ?? null,

            'Words and Phrases to Avoid' =>
                $brand['words_to_avoid'] ?? null,

            /*
            |--------------------------------------------------------------------------
            | Samples are style references, not language instructions
            |--------------------------------------------------------------------------
            */

            'Caption Samples — Style Reference Only' =>
                $brand['caption_samples'] ?? null,

            'Caption Sample Usage Rule' =>
                filled($brand['caption_samples'] ?? null)
                    ? 'Use samples only for energy, rhythm, pacing, personality, '
                        . 'sentence flow, and emoji density. Do not copy their language, '
                        . 'dialect, audience gender, facts, or complete sentences.'
                    : null,

            /*
            |--------------------------------------------------------------------------
            | Conversion inventory
            |--------------------------------------------------------------------------
            */

            'Available Conversion Methods' =>
                $this->formatList(
                    $brand['conversion_actions'] ?? null
                ),

            'Conversion Details' =>
                $conversionDetails,
        ]);
    }

    public function persona(
        array $persona,
        ?string $name = null,
        ?string $ageRange = null
    ): string {
        $gender = $persona['gender'] ?? null;

        return $this->sections([
            'Persona Name' =>
                $name,

            'Age Range' =>
                $ageRange,

            'REQUIRED Audience Gender / Address' =>
                $gender,

            'Audience Address Rule' =>
                $this->audienceAddressRule($gender),

            'Who They Are' =>
                $persona['who']
                ?? $persona['description']
                ?? null,

            'Buyer Is the User' =>
                $persona['buyer_is_user'] ?? null,

            'Decision Maker / Person Who Pays' =>
                $persona['decider'] ?? null,

            'Priorities' =>
                $this->formatList(
                    $persona['priorities'] ?? null
                ),

            'Objections / Reasons for Hesitation' =>
                $persona['objection'] ?? null,
        ]);
    }

    protected function audienceAddressRule(
        mixed $gender
    ): ?string {
        if (! is_string($gender) || blank($gender)) {
            return null;
        }

        $normalized = mb_strtolower($gender);

        if (
            str_contains($normalized, 'mixed') ||
            str_contains($normalized, 'everyone') ||
            str_contains($normalized, 'inclusive')
        ) {
            return 'Use inclusive plural forms consistently throughout every caption. '
                . 'Do not use feminine-only or masculine-only addressing.';
        }

        if (
            str_contains($normalized, 'women') ||
            str_contains($normalized, 'female')
        ) {
            return 'Use feminine addressing consistently throughout every caption.';
        }

        if (
            str_contains($normalized, 'men') ||
            str_contains($normalized, 'male')
        ) {
            return 'Use masculine addressing consistently throughout every caption.';
        }

        return 'Use the audience address specified above consistently throughout the campaign.';
    }

    /**
     * Format labeled sections and omit null, empty or unusable values.
     */
    protected function sections(array $sections): string
    {
        return collect($sections)
            ->map(function (mixed $value, string $label) {
                $formatted = $this->formatValue($value);

                if ($formatted === null) {
                    return null;
                }

                return mb_strtoupper($label) . "\n"
                    . $formatted;
            })
            ->filter()
            ->implode("\n\n");
    }

    /**
     * Safely format scalar, array or object values.
     */
    protected function formatValue(
        mixed $value
    ): ?string {
        if ($value === null) {
            return null;
        }

        if (is_bool($value)) {
            return $value ? 'Yes' : 'No';
        }

        if (is_array($value)) {
            return $this->formatList($value);
        }

        if (is_object($value)) {
            $value = (array) $value;

            return $this->formatKeyValueList($value);
        }

        $value = trim((string) $value);

        return $value !== '' ? $value : null;
    }

    /**
     * Convert either a string or an array into a readable comma-separated list.
     */
    protected function formatList(
        mixed $value
    ): ?string {
        if ($value === null) {
            return null;
        }

        $items = is_array($value)
            ? $value
            : [$value];

        $formattedItems = collect($items)
            ->map(function (mixed $item) {
                if (is_array($item) || is_object($item)) {
                    return $this->formatKeyValueList(
                        (array) $item
                    );
                }

                return trim((string) $item);
            })
            ->filter(fn (?string $item) =>
                filled($item)
            )
            ->unique()
            ->values();

        return $formattedItems->isNotEmpty()
            ? $formattedItems->implode(', ')
            : null;
    }

    /**
     * Convert associative arrays into readable labeled lines.
     */
    protected function formatKeyValueList(
        mixed $values
    ): ?string {
        if (! is_array($values)) {
            return $this->formatValue($values);
        }

        $lines = collect($values)
            ->map(function (mixed $value, mixed $key) {
                $formatted = $this->formatValue($value);

                if ($formatted === null) {
                    return null;
                }

                $label = str($key)
                    ->replace('_', ' ')
                    ->title()
                    ->toString();

                return "{$label}: {$formatted}";
            })
            ->filter();

        return $lines->isNotEmpty()
            ? $lines->implode("\n")
            : null;
    }

    /**
     * Combine several optional values without leaving empty separators.
     */
    protected function combineValues(
        array $values
    ): ?string {
        $formatted = collect($values)
            ->map(fn (mixed $value) =>
                $this->formatValue($value)
            )
            ->filter()
            ->unique()
            ->values();

        return $formatted->isNotEmpty()
            ? $formatted->implode(', ')
            : null;
    }
}