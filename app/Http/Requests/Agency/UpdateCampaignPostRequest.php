<?php

namespace App\Http\Requests\Agency;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCampaignPostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'caption' => [
                'required',
                'string',
            ],

            'hashtags' => [
                'nullable',
                'string',
            ],

            'creative_direction' => [
                'nullable',
                'string',
            ],
        ];
    }
}