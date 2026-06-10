<?php

namespace App\Http\Requests\Admin;

use App\Models\DiscountCode;
use Illuminate\Validation\Rule;

class UpdateDiscountCodeRequest extends StoreDiscountCodeRequest
{
    public function rules(): array
    {
        $rules = parent::rules();
        $discountCode = $this->route('discountCode');

        $rules['code'] = [
            'required',
            'string',
            'max:50',
            'regex:/^[A-Z0-9_-]+$/',
            Rule::unique(DiscountCode::class, 'code')->ignore($discountCode?->id),
        ];

        return $rules;
    }
}
