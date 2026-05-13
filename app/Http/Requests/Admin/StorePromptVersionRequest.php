<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StorePromptVersionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isFounder() === true;
    }

    public function rules(): array
    {
        return [
            'prompt_template_id' => ['required', 'exists:prompt_templates,id'],

            'content' => ['required', 'string', 'max:50000'],

            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }
}