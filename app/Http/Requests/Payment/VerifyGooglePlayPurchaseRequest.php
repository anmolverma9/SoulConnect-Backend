<?php

namespace App\Http\Requests\Payment;

use Illuminate\Foundation\Http\FormRequest;

class VerifyGooglePlayPurchaseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_id' => ['required', 'string', 'max:255'],
            'purchase_token' => ['required', 'string', 'max:512'],
            'order_id' => ['nullable', 'string', 'max:255'],
        ];
    }
}
