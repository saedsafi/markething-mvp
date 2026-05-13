<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class TestPromptRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isFounder() === true;
    }

    public function rules(): array
    {
        return [
            'prompt' => ['required', 'string', 'max:50000'],

            'test_input' => ['required', 'string', 'max:12000'],
        ];
    }
}