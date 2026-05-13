<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreAgencyUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isFounder() === true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'temporary_password' => ['required', 'string', 'min:8'],
            'client_limit' => ['required', 'integer', 'min:1', 'max:999'],
        ];
    }
}