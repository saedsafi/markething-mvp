<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'max_campaign_days' => ['required', 'integer', 'min:1', 'max:90'],
            'max_personas_per_client' => ['required', 'integer', 'min:1', 'max:5'],
            'max_regenerations_per_post' => ['required', 'integer', 'min:1', 'max:1'],
            'ai_assist_daily_limit' => ['required', 'integer', 'min:1', 'max:50'],
            'business_context_character_limit' => ['required', 'integer', 'min:1', 'max:5000'],
        ];
    }

    public function messages(): array
    {
        return [

            'max_campaign_days.max' =>
                'Maximum campaign range cannot exceed 90 days.',

        ];
    }
}