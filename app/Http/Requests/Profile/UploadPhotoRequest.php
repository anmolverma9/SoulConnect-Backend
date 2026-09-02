<?php

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;

class UploadPhotoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'photo' => [
                'required',
                'file',
                'image',
                'mimes:jpeg,png,webp,jpg',
                'max:10240', // 10MB
            ],
            'is_primary' => ['sometimes', 'boolean'],
        ];
    }
}
