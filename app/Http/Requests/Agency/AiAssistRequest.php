<?php

namespace App\Http\Requests\Agency;

use Illuminate\Foundation\Http\FormRequest;

class AiAssistRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAgency() === true;
    }

    public function rules(): array
    {
        return [
            'field' => ['required', 'string', 'max:120'],

            'context' => ['nullable', 'string', 'max:12000'],

            'client_name' => ['nullable', 'string', 'max:255'],

            'industry' => ['nullable', 'string', 'max:255'],
        ];
    }
}