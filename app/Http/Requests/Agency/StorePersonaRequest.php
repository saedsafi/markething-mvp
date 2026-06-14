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
            'name' => ['required', 'string', 'max:50'],
            'gender' => ['required', 'string'],
            'age_range' => ['required', 'string'],
            'who' => ['required', 'string', 'max:80'],
            'buyer_is_user' => ['required', 'string'],
            'decider' => ['nullable', 'string', 'max:60'],
            'priorities' => ['nullable', 'array'],
            'objection' => ['nullable', 'string', 'max:150'],
        ];
    }

    public function withValidator($validator): void
{
    $validator->after(function ($validator) {

        if (
            $this->input('buyer_is_user') !== 'Yes — they buy it and use it themselves'
            && ! $this->filled('decider')
        ) {
            $validator->errors()->add(
                'decider',
                'Please specify who decides or pays.'
            );
        }
    });
}
}