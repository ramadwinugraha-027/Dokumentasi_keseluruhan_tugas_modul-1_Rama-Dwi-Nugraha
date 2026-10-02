<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreActivityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:20', 'unique:activities,code'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'title' => ['required', 'string', 'min:5', 'max:100'],
            'activity_date' => ['required', 'date'],
            'description' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'code.required' => 'Kode kegiatan wajib diisi.',
            'code.unique' => 'Kode kegiatan sudah digunakan.',
            'category_id.required' => 'Kategori kegiatan wajib dipilih.',
            'category_id.exists' => 'Kategori yang dipilih tidak valid atau tidak ditemukan.',
            'title.required' => 'Judul kegiatan wajib diisi.',
            'title.min' => 'Judul kegiatan minimal 5 karakter.',
            'activity_date.required' => 'Tanggal kegiatan wajib diisi.',
        ];
    }
}
