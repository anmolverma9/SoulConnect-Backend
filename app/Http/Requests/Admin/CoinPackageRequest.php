<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CoinPackageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $packageId = $this->route('package')?->id ?? $this->route('package');

        return [
            'name' => ['required', 'string', 'max:100'],
            'coins' => ['required', 'integer', 'min:1'],
            'bonus_coins' => ['sometimes', 'integer', 'min:0'],
            'price' => ['required', 'numeric', 'min:0'],
            'currency' => ['sometimes', 'string', 'size:3'],
            'google_product_id' => ['required', 'string', 'max:255', 'unique:coin_packages,google_product_id,' . $packageId],
            'is_active' => ['sometimes', 'boolean'],
            'sort_order' => ['sometimes', 'integer', 'min:0'],
        ];
    }
}
