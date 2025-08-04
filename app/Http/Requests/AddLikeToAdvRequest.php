<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddLikeToAdvRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'adv_id' => 'required|exists:advs,id'
        ];
    }

    public function messages()
    {
        return [
            'adv_id.required' => 'this id is required',
            'adv_id.exists' => 'this id must be exist in advs table'
        ];
    }
}
