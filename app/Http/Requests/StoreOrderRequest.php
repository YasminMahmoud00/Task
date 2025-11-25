<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_ids' => 'required|array|min:1',
            'product_ids.*' => 'required|integer|exists:products,id',
        ];
    }

    public function messages(): array
    {
        return [
            'product_ids.required' => 'المنتجات مطلوبة',
            'product_ids.array' => 'المنتجات يجب أن تكون مصفوفة',
            'product_ids.min' => 'يجب اختيار منتج واحد على الأقل',
            'product_ids.*.exists' => 'بعض المنتجات غير موجودة'
        ];
    }
}
