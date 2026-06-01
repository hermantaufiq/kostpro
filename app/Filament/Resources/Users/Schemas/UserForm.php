<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Section::make('Informasi Pribadi')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama Lengkap')
                            ->required(),
                        TextInput::make('email')
                            ->label('Email Address')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true),
                        TextInput::make('phone')
                            ->label('No. Telepon (WhatsApp)')
                            ->tel(),
                        TextInput::make('nik')
                            ->label('NIK KTP')
                            ->numeric(),
                        Textarea::make('alamat')
                            ->label('Alamat Lengkap')
                            ->columnSpanFull(),
                        \Filament\Forms\Components\FileUpload::make('foto_ktp_url')
                            ->label('Foto KTP')
                            ->image()
                            ->directory('ktp-images')
                            ->columnSpanFull(),
                    ])->columns(2),

                \Filament\Forms\Components\Section::make('Akun & Keamanan')
                    ->schema([
                        Select::make('user_type')
                            ->label('Tipe Pengguna')
                            ->options(['admin' => 'Admin', 'tenant' => 'Tenant'])
                            ->default('tenant')
                            ->required(),
                        Toggle::make('is_active')
                            ->label('Status Akun Aktif')
                            ->default(true)
                            ->required(),
                        TextInput::make('password')
                            ->password()
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $context): bool => $context === 'create'),
                    ])->columns(2),
            ]);
    }
}
