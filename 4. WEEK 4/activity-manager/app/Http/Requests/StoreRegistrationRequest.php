<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRegistrationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'participant_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:100'],
            'participant_phone' => ['nullable', 'string', 'max:20'],
        ];
    }

    public function messages(): array
    {
        return [
            'participant_name.required' => 'Nama peserta wajib diisi.',
            'email.required' => 'Email peserta wajib diisi.',
            'email.email' => 'Format email tidak valid.',
        ];
    }
}
