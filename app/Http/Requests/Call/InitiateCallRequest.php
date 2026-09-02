<?php

namespace App\Http\Requests\Call;

use Illuminate\Foundation\Http\FormRequest;

class InitiateCallRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'receiver_id' => ['required', 'integer', 'exists:users,id'],
            'type' => ['sometimes', 'string', 'in:voice,video'],
            'conversation_id' => ['nullable', 'integer', 'exists:conversations,id'],
        ];
    }
}
