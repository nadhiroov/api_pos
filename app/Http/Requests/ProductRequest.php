<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
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
        $productId = $this->route('product');
        return [
            'category_id' => 'required|exists:categories,id',
            'branch_id'   => 'required|array',
            'branch_id.*' => 'exists:branches,id',
            'name'        => 'required|string|max:255',
            'sku'         => [
                'required',
                'string',
                'max:30',
                // ignore current product ID
                Rule::unique('products', 'sku')->ignore($productId),
            ],
            'unit'        => 'nullable|string|max:20',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'nullable|integer|min:0',
            'barcode'     => 'nullable|string',
        ];
    }
}
