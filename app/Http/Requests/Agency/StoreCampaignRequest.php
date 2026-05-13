<?php

namespace App\Http\Requests\Agency;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCampaignRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAgency() === true;
    }

    public function rules(): array
    {
        return [
            'client_id' => ['required', 'integer', 'exists:clients,id'],
            'persona_id' => ['required', 'integer', 'exists:personas,id'],

            'name' => ['required', 'string', 'max:255'],
            'objective' => ['required', 'string', 'max:255'],

            'start_date' => ['required', 'date', 'after_or_equal:today'],
            'end_date' => ['required', 'date', 'after:start_date'],

            'channels' => ['required', 'array', 'min:1'],
            'channels.*' => [
                'required',
                'string',
                Rule::in(['instagram', 'facebook']),
            ],

            'requested_posts_count' => ['required', 'integer', 'min:1'],

            'description' => ['nullable', 'string', 'max:3000'],
        ];
    }

    public function messages(): array
    {
        return [
            'channels.required' => 'Please select at least one channel.',
            'channels.min' => 'Please select at least one channel.',
            'requested_posts_count.required' => 'Please enter the number of posts.',
        ];
    }
}