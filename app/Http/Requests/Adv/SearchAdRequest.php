<?php

namespace App\Http\Requests\Adv;

use Illuminate\Foundation\Http\FormRequest;

class SearchAdRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'description' => 'sometimes|string',
            'location' => 'sometimes|string|max:255',
            'category_id' => 'sometimes|integer|exists:categories,id',
            'min_price' => 'sometimes|numeric|min:0',
            'max_price' => 'sometimes|numeric|min:0',
        ];
    }

    public function messages()
    {
        return [
            'location.string' => 'المدينة يجب أن تكون نصية',
            'category_id.integer' => 'التصنيف يجب أن يكون رقمًا صحيحًا',
            'category_id.exists' => 'التصنيف المختار غير موجود',
            'min_price.numeric' => 'أقل سعر يجب أن يكون رقمًا',
            'max_price.numeric' => 'أعلى سعر يجب أن يكون رقمًا',
        ];
    }
} 