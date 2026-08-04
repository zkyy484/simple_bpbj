<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePertanyaanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'pertanyaan' => [
                'required',
                'string',
                'max:1000',
            ],

            'tipe_pertanyaan' => [
                'required',
                'in:rating,pilihan_ganda,textarea',
            ],

            'urutan' => [
                'required',
                'integer',
                'min:1',
            ],

            'status' => [
                'required',
                'in:aktif,nonaktif',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'pertanyaan.required' => 'Pertanyaan wajib diisi.',
            'pertanyaan.max' => 'Pertanyaan maksimal 1000 karakter.',

            'tipe_pertanyaan.required' => 'Tipe pertanyaan wajib dipilih.',
            'tipe_pertanyaan.in' => 'Tipe pertanyaan tidak valid.',

            'urutan.required' => 'Urutan wajib diisi.',
            'urutan.integer' => 'Urutan harus berupa angka.',
            'urutan.min' => 'Urutan minimal 1.',

            'status.required' => 'Status wajib dipilih.',
            'status.in' => 'Status tidak valid.',
        ];
    }

    public function attributes(): array
    {
        return [
            'pertanyaan' => 'Pertanyaan',
            'tipe_pertanyaan' => 'Tipe Pertanyaan',
            'urutan' => 'Urutan',
            'status' => 'Status',
        ];
    }
}