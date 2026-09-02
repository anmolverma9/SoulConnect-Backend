<?php

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'min:2', 'max:100'],
            'date_of_birth' => ['sometimes', 'date', 'before:-18 years'],
            'gender' => ['sometimes', 'string', 'in:male,female,non_binary,other'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'city' => ['nullable', 'string', 'max:100'],
            'country' => ['nullable', 'string', 'max:100'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'occupation' => ['nullable', 'string', 'max:100'],
            'education' => ['nullable', 'string', 'max:100'],
            'height' => ['nullable', 'integer', 'between:100,250'],
            'interests' => ['nullable', 'array', 'max:20'],
            'interests.*' => ['string', 'max:50'],
            'relationship_goal' => ['nullable', 'string', 'in:long_term,short_term,casual,friendship,marriage'],
            'profile_visibility' => ['sometimes', 'string', 'in:public,hidden,incognito'],
        ];
    }
}
