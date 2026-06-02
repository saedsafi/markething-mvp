<?php

namespace App\Http\Requests\Agency;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAgency() === true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'industry' => ['nullable', 'string', 'max:255'],
            'business_context' => ['nullable', 'string', 'max:5000'],

            'business_offer' => ['nullable', 'string', 'max:5000'],
            'brand_voice' => ['nullable', 'string', 'max:255'],
            'brand_personality' => ['nullable', 'string', 'max:5000'],
            'brand_values' => ['nullable', 'string', 'max:5000'],
        ];
    }
}