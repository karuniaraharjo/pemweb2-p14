<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\KodeBukuFormat;

class UpdateBukuRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Get buku ID from route parameter
        $bukuId = $this->route('buku');

        return [
            'kode_buku' => ['required', 'string', 'max:20', 'unique:buku,kode_buku,' . $bukuId, new KodeBukuFormat()],
            'judul' => 'required|string|max:200',
            'kategori' => 'required|in:Programming,Database,Web Design,Networking,Data Science',
            'pengarang' => 'required|string|max:100',
            'penerbit' => 'required|string|max:100',
            'tahun_terbit' => 'required|integer|min:1900|max:' . date('Y'),
            'isbn' => 'nullable|string|max:20',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'deskripsi' => 'nullable|string',
            'bahasa' => 'required|string|max:20',
        ];
    }

    /**
     * Configure the validator instance with custom conditional validations.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Conditional: Jika kategori "Programming", field bahasa harus "Inggris"
            if ($this->input('kategori') === 'Programming' && $this->input('bahasa') !== 'Inggris') {
                $validator->errors()->add('bahasa', 'Buku kategori Programming harus menggunakan bahasa Inggris.');
            }

            // Conditional: Jika tahun terbit < 2000, stok maksimal 5
            if ($this->input('tahun_terbit') < 2000 && $this->input('stok') > 5) {
                $validator->errors()->add('stok', 'Buku terbit sebelum tahun 2000 hanya boleh memiliki stok maksimal 5 buah.');
            }
        });

        return $validator;
    }

    /**
     * Get custom error messages.
     */
    public function messages(): array
    {
        return [
            'kode_buku.required' => 'Kode buku wajib diisi.',
            'kode_buku.unique' => 'Kode buku sudah digunakan.',
            'judul.required' => 'Judul buku wajib diisi.',
            'kategori.required' => 'Kategori wajib dipilih.',
            'harga.required' => 'Harga buku wajib diisi.',
            'harga.numeric' => 'Harga harus berupa angka.',
            'stok.integer' => 'Stok harus berupa angka bulat.',
        ];
    }
}
