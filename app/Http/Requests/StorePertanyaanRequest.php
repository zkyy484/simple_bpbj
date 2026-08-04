<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePertanyaanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation Rules
     */
    public function rules(): array
    {
        return [

            // Pertanyaan
            'pertanyaan' => [
                'required',
                'string',
                'max:1000',
            ],

            // Tipe Pertanyaan
            'tipe_pertanyaan' => [
                'required',
                Rule::in([
                    'rating',
                    'pilihan_ganda',
                    'textarea',
                ]),
            ],

            // Urutan
            'urutan' => [
                'required',
                'integer',
                'min:1',
            ],

            /*
            |--------------------------------------------------------------------------
            | Opsi Rating
            |--------------------------------------------------------------------------
            */

            'rating_opsi' => [
                'required_if:tipe_pertanyaan,rating',
                'array',
                'size:5',
            ],

            'rating_opsi.*.label' => [
                'required_if:tipe_pertanyaan,rating',
                'string',
                'max:100',
            ],

            'rating_opsi.*.nilai' => [
                'required_if:tipe_pertanyaan,rating',
                'integer',
                'between:1,4',
            ],

            /*
            |--------------------------------------------------------------------------
            | Opsi Pilihan Ganda
            |--------------------------------------------------------------------------
            */

            'opsi' => [
                'required_if:tipe_pertanyaan,pilihan_ganda',
                'array',
                'min:2',
            ],

            'opsi.*' => [
                'required_if:tipe_pertanyaan,pilihan_ganda',
                'string',
                'max:255',
                'distinct',
            ],

        ];
    }

    /**
     * Error Messages
     */
    public function messages(): array
    {
        return [

            'pertanyaan.required' => 'Pertanyaan wajib diisi.',

            'tipe_pertanyaan.required' => 'Tipe pertanyaan wajib dipilih.',

            'urutan.required' => 'Urutan wajib diisi.',

            'status.required' => 'Status wajib dipilih.',

            'rating_opsi.required_if' => 'Deskripsi rating wajib diisi.',

            'rating_opsi.size' => 'Rating harus terdiri dari 5 tingkatan.',

            'rating_opsi.*.label.required_if' => 'Label rating wajib diisi.',

            'rating_opsi.*.nilai.required_if' => 'Nilai rating wajib diisi.',

            'opsi.required_if' => 'Minimal terdapat dua opsi.',

            'opsi.min' => 'Minimal terdapat dua opsi.',

            'opsi.*.required_if' => 'Opsi tidak boleh kosong.',

            'opsi.*.distinct' => 'Terdapat opsi yang sama.',

        ];
    }

    /**
     * Attribute Names
     */
    public function attributes(): array
    {
        return [

            'pertanyaan' => 'Pertanyaan',

            'tipe_pertanyaan' => 'Tipe Pertanyaan',

            'urutan' => 'Urutan',

            'status' => 'Status',

            'opsi' => 'Opsi',

            'rating_opsi' => 'Deskripsi Rating',

        ];
    }
}