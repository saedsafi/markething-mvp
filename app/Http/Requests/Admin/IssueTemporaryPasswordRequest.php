<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class IssueTemporaryPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isFounder() === true;
    }

    public function rules(): array
    {
        return [
            'temporary_password' => ['required', 'string', 'min:8'],
        ];
    }
}