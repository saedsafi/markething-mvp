<?php

namespace App\Http\Requests\Agency;

use Illuminate\Foundation\Http\FormRequest;

class StorePersonaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAgency() === true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'age_range' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:700'],
            'channel' => ['nullable', 'string', 'max:255'],
        ];
    }
}