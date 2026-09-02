<?php

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePreferencesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'preferred_gender' => ['sometimes', 'string', 'in:all,male,female,non_binary'],
            'minimum_age' => ['sometimes', 'integer', 'min:18', 'max:100'],
            'maximum_age' => ['sometimes', 'integer', 'min:18', 'max:100', 'gte:minimum_age'],
            'maximum_distance' => ['sometimes', 'integer', 'min:1', 'max:500'],
            'relationship_goal' => ['nullable', 'string', 'in:long_term,short_term,casual,friendship,marriage'],
            'interests' => ['nullable', 'array'],
            'interests.*' => ['string', 'max:50'],
        ];
    }
}
