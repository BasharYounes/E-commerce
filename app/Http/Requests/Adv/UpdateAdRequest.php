<?php

namespace App\Http\Requests\Adv;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAdRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'image' => 'sometimes|string|mimes:jpeg,png,jpg,gif,svg',
            'price' => 'sometimes|numeric|min:0',
            'location' => 'sometimes|string|max:255',
            'category_id' => 'sometimes|exists:categories,id',
            'description' => 'sometimes|string',
            'is_active' => 'sometimes|boolean',
            'phone' => 'sometimes|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'image.string' => 'رابط الصورة يجب أن يكون نصيًا',
            'price.numeric' => 'السعر يجب أن يكون رقمًا',
            'location.string' => 'الموقع يجب أن يكون نصيًا',
            'category_id.exists' => 'التصنيف غير موجود',
            'description.string' => 'الوصف يجب أن يكون نصيًا',
        ];
    }
} 