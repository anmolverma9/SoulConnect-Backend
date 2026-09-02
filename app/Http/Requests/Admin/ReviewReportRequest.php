<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ReviewReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => ['required', 'string', 'in:reviewing,resolved,dismissed'],
            'resolution_notes' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
