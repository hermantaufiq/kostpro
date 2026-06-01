<?php

namespace App\Filament\Resources\Penyewaans\Schemas;

use App\Enums\StatusPenyewaan;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class PenyewaanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                Select::make('kamar_id')
                    ->relationship('kamar', 'id')
                    ->required(),
                TextInput::make('kode_penyewaan')
                    ->required(),
                DatePicker::make('tanggal_masuk')
                    ->required(),
                DatePicker::make('tanggal_keluar'),
                TextInput::make('durasi_bulan')
                    ->required()
                    ->numeric()
                    ->default(1),
                Select::make('status')
                    ->options(StatusPenyewaan::class)
                    ->default('pending')
                    ->required(),
                Textarea::make('catatan_penyewa')
                    ->columnSpanFull(),
                Textarea::make('catatan_admin')
                    ->columnSpanFull(),
                TextInput::make('approved_by')
                    ->numeric(),
                DateTimePicker::make('tanggal_approval'),
                TextInput::make('ktp_url')
                    ->url(),
                TextInput::make('ktp_path'),
                TextInput::make('kontrak_url')
                    ->url(),
                TextInput::make('harga_bulanan_snapshot')
                    ->required()
                    ->numeric(),
                TextInput::make('deposit_amount')
                    ->required()
                    ->numeric()
                    ->default(0),
                Toggle::make('deposit_paid')
                    ->required(),
                DateTimePicker::make('deposit_paid_at'),
                DateTimePicker::make('checkin_at'),
                DateTimePicker::make('checkout_at'),
            ]);
    }
}
