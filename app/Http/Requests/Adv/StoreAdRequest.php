<?php

namespace App\Http\Requests\Adv;

use Illuminate\Foundation\Http\FormRequest;

class StoreAdRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'image' => 'sometimes|mimes:jpeg,png,jpg,gif,svg',
            'price' => 'required|numeric|min:0',
            'location' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string',
            'is_active' => 'boolean',
            'phone' => 'required|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'image.required' => 'يجب إدخال رابط الصورة',
            'price.required' => 'يجب إدخال السعر',
            'location.required' => 'يجب إدخال الموقع',
            'category_id.required' => 'يجب اختيار التصنيف',
            'category_id.exists' => 'التصنيف غير موجود',
            // 'published_duration.required' => 'يجب تحديد مدة النشر', // احذف أو اجعلها اختيارية
            'description.required' => 'يجب إدخال الوصف',
        ];
    }
}
