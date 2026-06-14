<?php

namespace App\Http\Requests\Agency;

use App\Services\AppSettingService;
use Illuminate\Foundation\Http\FormRequest;

class UpdateClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAgency() === true;
    }

    public function rules(): array
    {
        $businessContextLimit = app(AppSettingService::class)
            ->int('business_context_character_limit', 5000);

        return [
            'name' => ['required', 'string', 'max:255'],

            'business_context' => ['nullable', 'string', 'max:' . $businessContextLimit],

            'industry' => ['required', 'string', 'max:255'],
            'industry_other' => ['nullable', 'string', 'max:50'],

            'business_type' => ['required', 'string', 'max:255'],
            'business_type_other' => ['nullable', 'string', 'max:50'],

            'country' => ['required', 'string', 'max:255'],
            'city' => ['nullable', 'array'],
            'city.*' => ['string', 'max:255'],

            'price_tier' => ['required', 'integer', 'min:1', 'max:5'],

            'differentiator' => ['required', 'string', 'max:200'],

            'brand_positioning' => ['required', 'array', 'min:1', 'max:2'],
            'brand_positioning.*' => ['string', 'max:255'],

            'brand_avoids' => ['nullable', 'array', 'max:3'],
            'brand_avoids.*' => ['string', 'max:255'],
            'brand_avoids_other' => ['nullable', 'string', 'max:60'],

            'business_age' => ['required', 'string', 'max:255'],

            'arabic_dialect' => ['required', 'string', 'max:255'],
            'emoji_usage' => ['required', 'string', 'max:255'],
            'english_usage' => ['required', 'string', 'max:255'],

            'words_to_avoid' => ['nullable', 'string', 'max:150'],
            'caption_samples' => ['nullable', 'string', 'max:5000'],

            'conversion_actions' => ['required', 'array', 'min:1'],
            'conversion_actions.*' => ['string', 'max:255'],

            'conversion_location' => ['nullable', 'string', 'max:255'],
            'conversion_whatsapp' => ['nullable', 'string', 'max:255'],
            'conversion_phone' => ['nullable', 'string', 'max:255'],
            'conversion_delivery_app' => ['nullable', 'string', 'max:255'],
            'conversion_website' => ['nullable', 'string', 'max:255'],
            'conversion_booking' => ['nullable', 'string', 'max:255'],
            'conversion_social_dm' => ['nullable', 'string', 'max:255'],
            'conversion_signup' => ['nullable', 'string', 'max:255'],

            'persona_name' => ['nullable', 'string', 'max:50'],
            'persona_gender' => ['nullable', 'string', 'max:255'],
            'persona_age_range' => ['nullable', 'string', 'max:255'],
            'persona_who' => ['nullable', 'string', 'max:80'],
            'persona_buyer_is_user' => ['nullable', 'string', 'max:255'],
            'persona_decider' => ['nullable', 'string', 'max:60'],
            'persona_priorities' => ['nullable', 'array', 'max:2'],
            'persona_priorities.*' => ['string', 'max:255'],
            'persona_objection' => ['nullable', 'string', 'max:150'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $actions = $this->input('conversion_actions', []);

            $requiredDetails = [
                'Visit the store / location' => 'conversion_location',
                'Order / inquire via WhatsApp' => 'conversion_whatsapp',
                'Call us' => 'conversion_phone',
                'Order via delivery app' => 'conversion_delivery_app',
                'Buy on our website' => 'conversion_website',
                'Book an appointment' => 'conversion_booking',
                'Subscribe / sign up' => 'conversion_signup',
            ];

            foreach ($requiredDetails as $action => $field) {
                if (in_array($action, $actions, true) && ! $this->filled($field)) {
                    $validator->errors()->add(
                        $field,
                        'This detail is required for the selected conversion action.'
                    );
                }
            }

            if (
                $this->filled('persona_buyer_is_user')
                && $this->input('persona_buyer_is_user') !== 'Yes — they buy it and use it themselves'
                && ! $this->filled('persona_decider')
            ) {
                $validator->errors()->add(
                    'persona_decider',
                    'Please specify who decides or pays.'
                );
            }
        });

        if ($this->input('country') === 'Palestine' && empty($this->input('city'))) {
            $validator->errors()->add(
                'city',
                'Please select at least one city for Palestine.'
            );
        }
    }
}