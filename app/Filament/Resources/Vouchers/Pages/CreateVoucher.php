<?php

namespace App\Filament\Resources\Vouchers\Pages;

use App\Filament\Resources\Vouchers\VoucherResource;
use App\Enums\StatusVoucher;
use App\Enums\TipeNotifikasi;
use App\Models\Notifikasi;
use App\Services\Voucher\VoucherService;
use Filament\Resources\Pages\CreateRecord;

class CreateVoucher extends CreateRecord
{
    protected static string $resource = VoucherResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (empty($data['kode_voucher'])) {
            $data['kode_voucher'] = app(VoucherService::class)->generateUniqueKode();
        }

        $data['status'] ??= StatusVoucher::Aktif;
        $data['sumber'] ??= 'manual_admin';

        return $data;
    }

    protected function afterCreate(): void
    {
        $voucher = $this->record;

        Notifikasi::create([
            'user_id' => $voucher->user_id,
            'tipe' => TipeNotifikasi::Sistem,
            'judul' => 'Voucher Apresiasi Baru! 🎉',
            'pesan' => "Anda mendapat voucher apresiasi Rp " . number_format($voucher->nominal_diskon, 0, ',', '.') .
                       " dari admin. Kode: {$voucher->kode_voucher}. Berlaku hingga " .
                       $voucher->berlaku_sampai->format('d M Y') . ".",
            'read_at' => null,
        ]);
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
