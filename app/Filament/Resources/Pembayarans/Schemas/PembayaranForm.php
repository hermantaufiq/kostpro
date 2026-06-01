<?php

namespace App\Filament\Resources\Pembayarans\Schemas;

use App\Enums\MetodePembayaran;
use App\Enums\StatusPembayaran;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PembayaranForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Section::make('Informasi Pembayaran')
                    ->schema([
                        Select::make('tagihan_id')
                            ->label('No. Invoice')
                            ->relationship('tagihan', 'kode_tagihan')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Select::make('user_id')
                            ->label('Penyewa')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        TextInput::make('kode_pembayaran')
                            ->label('Kode Pembayaran')
                            ->default(fn () => 'PAY-' . date('Ymd') . '-' . strtoupper(uniqid()))
                            ->readOnly()
                            ->required(),
                        Select::make('status')
                            ->options(StatusPembayaran::class)
                            ->default('pending')
                            ->required(),
                    ])->columns(2),

                \Filament\Forms\Components\Section::make('Detail Transaksi')
                    ->schema([
                        Select::make('metode')
                            ->options(MetodePembayaran::class),
                        TextInput::make('channel_code')
                            ->label('Channel Pembayaran')
                            ->placeholder('Contoh: BCA, BNI, OVO'),
                        TextInput::make('jumlah')
                            ->label('Jumlah Bayar')
                            ->required()
                            ->numeric()
                            ->prefix('Rp'),
                        TextInput::make('biaya_admin')
                            ->label('Biaya Admin')
                            ->required()
                            ->numeric()
                            ->default(0)
                            ->prefix('Rp'),
                        TextInput::make('jumlah_diterima')
                            ->label('Jumlah Diterima (Bersih)')
                            ->numeric()
                            ->prefix('Rp'),
                    ])->columns(3),

                \Filament\Forms\Components\Section::make('Xendit & Sistem')
                    ->schema([
                        TextInput::make('xendit_invoice_id')
                            ->label('Xendit Invoice ID'),
                        TextInput::make('xendit_external_id')
                            ->label('Xendit External ID'),
                        TextInput::make('xendit_payment_url')
                            ->label('URL Pembayaran Xendit')
                            ->url()
                            ->columnSpanFull(),
                        DateTimePicker::make('paid_at')
                            ->label('Waktu Dibayar'),
                        DateTimePicker::make('expired_at')
                            ->label('Batas Waktu Bayar'),
                    ])->columns(2),
            ]);
    }
}
