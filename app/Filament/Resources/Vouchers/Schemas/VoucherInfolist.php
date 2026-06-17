<?php

namespace App\Filament\Resources\Vouchers\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class VoucherInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('kode_voucher')
                    ->label('Kode Voucher')
                    ->copyable()
                    ->weight('bold')
                    ->fontFamily('mono'),
                TextEntry::make('user.name')
                    ->label('Penyewa'),
                TextEntry::make('nominal_diskon')
                    ->label('Nominal Diskon')
                    ->money('IDR'),
                TextEntry::make('status')
                    ->badge()
                    ->color(fn (\App\Enums\StatusVoucher $state): string => $state->color()),
                TextEntry::make('sumber')
                    ->label('Sumber')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pembayaran_tepat_waktu' => 'Bayar Tepat Waktu',
                        'streak_tepat_waktu'     => 'Streak 3× Tepat Waktu',
                        'manual_admin'           => 'Manual Admin',
                        default                  => ucwords(str_replace('_', ' ', $state)),
                    }),
                TextEntry::make('berlaku_sampai')
                    ->label('Berlaku Sampai')
                    ->dateTime('d M Y H:i'),
                TextEntry::make('digunakan_pada')
                    ->label('Digunakan Pada')
                    ->dateTime('d M Y H:i')
                    ->placeholder('Belum digunakan'),
                TextEntry::make('pembayaranSumber.kode_pembayaran')
                    ->label('Pembayaran Sumber')
                    ->placeholder('-'),
                TextEntry::make('tagihan.kode_tagihan')
                    ->label('Tagihan Digunakan')
                    ->placeholder('-'),
                TextEntry::make('pembayaran.kode_pembayaran')
                    ->label('Pembayaran Redeem')
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i'),
            ]);
    }
}
