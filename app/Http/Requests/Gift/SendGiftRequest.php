<?php

namespace App\Http\Requests\Gift;

use Illuminate\Foundation\Http\FormRequest;

class SendGiftRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'receiver_id' => ['required', 'integer', 'exists:users,id'],
            'gift_id' => ['required', 'integer', 'exists:gift_catalog,id'],
            'message' => ['nullable', 'string', 'max:255'],
        ];
    }
}
