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
            'client_id' => [
                'nullable',
                'integer',
                'exists:clients,id',
            ],

            'question_key' => [
                'required',
                'string',
                'max:120',
            ],

            'question_label' => [
                'required',
                'string',
                'max:255',
            ],

            'input' => [
                'nullable',
                'string',
                'max:5000',
            ],

            'extra_instructions' => [
                'nullable',
                'string',
                'max:5000',
            ],

            'character_limit' => [
                'nullable',
                'integer',
                'min:1',
                'max:5000',
            ],

            'business_context' => [
                'nullable',
                'string',
                'max:5000',
            ],

            'business_info' => [
                'nullable',
                'array',
            ],

            'brand_info' => [
                'nullable',
                'array',
            ],
        ];
    }
}