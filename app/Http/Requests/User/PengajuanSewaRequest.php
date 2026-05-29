<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class PengajuanSewaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'kamar_id' => ['required', 'exists:kamar,id'],
            'tanggal_masuk' => ['required', 'date', 'after_or_equal:today'],
            'durasi_bulan' => ['required', 'integer', 'min:1', 'max:12'],
            'catatan' => ['nullable', 'string', 'max:500'],
        ];
    }
}
