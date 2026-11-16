<?php

namespace Modules\Buku\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTitikRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null
            && $this->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'nama_titik' => ['required', 'string', 'max:255'],
            'kecamatan' => ['required', 'string', 'max:255'],
            'deskripsi' => ['required', 'string', 'max:255'],
            'kedalaman_cm' => ['required', 'integer', 'min:1', 'max:200'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama_titik.required' => 'Nama Titik buku wajib diisi.',
            'kecamatan.required' => 'Kecamatan wajib diisi.',
            'deskripsi.required' => 'deskripsi wajib diisi.',
            'kedalaman_cm.required' => 'Kedalaman air wajib diisi.',
        ];
    }
}
