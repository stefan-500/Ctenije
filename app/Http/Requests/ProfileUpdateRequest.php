<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'ime' => [
                'required',
                'string',
                'regex:/^[A-Z]{1}[a-zA-Z]{3,15}$/'
            ],
            'prezime' => [
                'required',
                'string',
                'regex:/^[A-Z]{1}[a-zA-Z]{3,15}$/'
            ],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id)
            ],
            'tel' => [
                'required',
                'string',
                'regex:/^\+3816([0-9]){6,9}$/'
            ],
            'adresa' => [
                'required',
                'string',
                'regex:/^[a-zA-Z]{1,15}(\s[a-zA-Z]{1,15})?(\s[a-zA-Z]{1,12})?(\s[a-zA-Z0-9]{1,20})?\s[a-zA-Z0-9]{1,3}\,\s[0-9]{5}\s[a-zA-z]{3,13}$/'
            ],
        ];
    }
}
