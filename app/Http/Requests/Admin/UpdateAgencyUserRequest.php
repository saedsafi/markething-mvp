<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAgencyUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isFounder() === true;
    }

    public function rules(): array
    {
        return [
            'client_limit' => ['required', 'integer', 'min:1', 'max:999'],
        ];
    }
}