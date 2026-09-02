<?php

namespace App\Http\Requests\Call;

use Illuminate\Foundation\Http\FormRequest;

class AcceptCallRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'signaling_data' => ['nullable', 'array'],
        ];
    }
}
