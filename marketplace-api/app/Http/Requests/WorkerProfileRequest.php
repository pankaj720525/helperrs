<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WorkerProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'address' => ['nullable', 'string', 'max:500'],
            'bio' => ['nullable', 'string', 'max:2000'],
            'phone_public' => ['nullable', 'string', 'max:20'],
            'email_public' => ['nullable', 'email', 'max:255'],
            'latitude' => ['nullable', 'numeric', 'min:-90', 'max:90'],
            'longitude' => ['nullable', 'numeric', 'min:-180', 'max:180'],
            'is_available' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'latitude.min' => 'Invalid latitude.',
            'latitude.max' => 'Invalid latitude.',
            'longitude.min' => 'Invalid longitude.',
            'longitude.max' => 'Invalid longitude.',
        ];
    }
}
