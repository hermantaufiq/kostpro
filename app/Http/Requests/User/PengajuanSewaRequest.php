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
        $user = $this->user();
        $ktpRule = $user && $user->foto_ktp_url
            ? ['nullable', 'image', 'max:2048']
            : ['required', 'image', 'max:2048'];

        return [
            'kamar_id'             => ['required', 'exists:kamar,id'],
            'tanggal_masuk'        => ['required', 'date', 'after_or_equal:today'],
            'durasi_bulan'         => ['required', 'integer', 'min:1', 'max:12'],
            'catatan'              => ['nullable', 'string', 'max:500'],
            'foto_ktp'             => $ktpRule,

            // Data Identitas Diri — wajib jika profil belum lengkap
            'nik'                  => ['required', 'string', 'digits:16'],
            'tanggal_lahir'        => ['required', 'date', 'before:today'],
            'jenis_kelamin'        => ['required', 'in:L,P'],
            'alamat'               => ['required', 'string', 'min:10', 'max:500'],
            'pekerjaan'            => ['required', 'string', 'max:100'],
            'asal_kota'            => ['required', 'string', 'max:100'],
            'kontak_darurat_nama'  => ['required', 'string', 'max:100'],
            'kontak_darurat_hp'    => ['required', 'string', 'max:20'],
        ];
    }

    public function messages(): array
    {
        return [
            'nik.required'                 => 'NIK (Nomor Induk Kependudukan) wajib diisi.',
            'nik.digits'                   => 'NIK harus terdiri dari 16 digit angka.',
            'tanggal_lahir.required'       => 'Tanggal lahir wajib diisi.',
            'tanggal_lahir.before'         => 'Tanggal lahir harus sebelum hari ini.',
            'jenis_kelamin.required'       => 'Jenis kelamin wajib dipilih.',
            'alamat.required'              => 'Alamat lengkap wajib diisi.',
            'alamat.min'                   => 'Alamat terlalu singkat, mohon isi dengan alamat lengkap.',
            'pekerjaan.required'           => 'Pekerjaan wajib diisi.',
            'asal_kota.required'           => 'Kota asal wajib diisi.',
            'kontak_darurat_nama.required' => 'Nama kontak darurat wajib diisi.',
            'kontak_darurat_hp.required'   => 'Nomor HP kontak darurat wajib diisi.',
            'foto_ktp.required'            => 'Foto KTP wajib diunggah.',
        ];
    }
}
