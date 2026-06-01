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
                Select::make('tagihan_id')
                    ->relationship('tagihan', 'id')
                    ->required(),
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                TextInput::make('kode_pembayaran')
                    ->required(),
                TextInput::make('xendit_invoice_id'),
                TextInput::make('xendit_external_id'),
                TextInput::make('xendit_payment_url')
                    ->url(),
                Select::make('metode')
                    ->options(MetodePembayaran::class),
                TextInput::make('channel_code'),
                TextInput::make('jumlah')
                    ->required()
                    ->numeric(),
                TextInput::make('biaya_admin')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('jumlah_diterima')
                    ->numeric(),
                Select::make('status')
                    ->options(StatusPembayaran::class)
                    ->default('pending')
                    ->required(),
                TextInput::make('payload_request'),
                TextInput::make('payload_response'),
                TextInput::make('payload_webhook'),
                DateTimePicker::make('paid_at'),
                DateTimePicker::make('expired_at'),
            ]);
    }
}
