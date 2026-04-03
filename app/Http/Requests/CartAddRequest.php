<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CartAddRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'qty' => ['nullable', 'integer', 'min:1'],
            'product_variant_id' => ['nullable', 'integer', 'exists:product_variants,id'],
            'sku' => ['nullable', 'string', 'max:120'],
            'buy_now' => ['nullable', 'boolean'],
        ];
    }
}

