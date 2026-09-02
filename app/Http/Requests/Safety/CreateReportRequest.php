<?php

namespace App\Http\Requests\Safety;

use Illuminate\Foundation\Http\FormRequest;

class CreateReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'reported_id' => ['nullable', 'integer', 'exists:users,id'],
            'reportable_type' => ['nullable', 'string', 'in:User,UserProfile,ProfilePhoto,Message,Call'],
            'reportable_id' => ['nullable', 'integer'],
            'reason' => ['required', 'string', 'max:255'],
            'details' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
