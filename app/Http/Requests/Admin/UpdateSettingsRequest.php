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

            'max_campaign_days' => [
                'required',
                'integer',
                'min:1',
                'max:90',
            ],

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