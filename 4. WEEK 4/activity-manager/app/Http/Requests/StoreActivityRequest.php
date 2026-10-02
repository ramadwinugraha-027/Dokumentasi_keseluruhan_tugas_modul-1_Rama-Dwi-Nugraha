<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreActivityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:20', 'unique:activities,code'], // Poin 5: unique untuk code pada create
            'category_id' => ['required', 'integer', 'exists:categories,id'],       // Poin 5: exists untuk category_id
            'title' => ['required', 'string', 'min:5', 'max:100'],
            'activity_date' => ['required', 'date'],
            'status' => ['required', Rule::in(['Planned', 'Ongoing', 'Done'])],
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
            'status.required' => 'Status kegiatan wajib dipilih.',
        ];
    }
}
