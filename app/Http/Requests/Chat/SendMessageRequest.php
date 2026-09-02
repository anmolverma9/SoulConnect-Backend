<?php

namespace App\Http\Requests\Chat;

use Illuminate\Foundation\Http\FormRequest;

class SendMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'body' => ['required_without:media', 'nullable', 'string', 'max:5000'],
            'type' => ['sometimes', 'string', 'in:text,image,audio,system'],
            'media' => ['sometimes', 'file', 'image', 'mimes:jpeg,png,webp,jpg', 'max:10240'],
        ];
    }
}
