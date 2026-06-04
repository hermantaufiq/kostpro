<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Models\User;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Section;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Grid;

class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                // =============================================
                // SECTION 1: Informasi Akun
                // =============================================
                Section::make('Informasi Akun')
                    ->icon('heroicon-o-user-circle')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('name')
                            ->label('Nama Lengkap')
                            ->weight('bold'),
                        TextEntry::make('email')
                            ->label('Alamat Email')
                            ->copyable(),
                        TextEntry::make('phone')
                            ->label('No. HP')
                            ->placeholder('-')
                            ->copyable(),
                        TextEntry::make('user_type')
                            ->label('Tipe Akun')
                            ->badge()
                            ->color(fn ($state) => match ($state) {
                                'admin' => 'danger',
                                'tenant' => 'success',
                                default => 'gray',
                            }),
                        IconEntry::make('is_active')
                            ->label('Status Aktif')
                            ->boolean(),
                        IconEntry::make('profil_lengkap')
                            ->label('Profil Lengkap')
                            ->boolean()
                            ->trueIcon('heroicon-o-check-badge')
                            ->falseIcon('heroicon-o-x-circle')
                            ->trueColor('success')
                            ->falseColor('warning'),
                    ]),

                // =============================================
                // SECTION 2: Data Identitas (KTP)
                // =============================================
                Section::make('Data Identitas (KTP)')
                    ->icon('heroicon-o-identification')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('nik')
                            ->label('NIK')
                            ->placeholder('Belum diisi')
                            ->copyable()
                            ->fontFamily('mono')
                            ->weight('bold'),
                        TextEntry::make('tanggal_lahir')
                            ->label('Tanggal Lahir')
                            ->date('d F Y')
                            ->placeholder('Belum diisi'),
                        TextEntry::make('jenis_kelamin')
                            ->label('Jenis Kelamin')
                            ->formatStateUsing(fn ($state) => match ($state) {
                                'L' => '♂ Laki-laki',
                                'P' => '♀ Perempuan',
                                default => '-',
                            })
                            ->placeholder('Belum diisi'),
                        TextEntry::make('pekerjaan')
                            ->label('Pekerjaan')
                            ->placeholder('Belum diisi'),
                        TextEntry::make('asal_kota')
                            ->label('Kota Asal')
                            ->placeholder('Belum diisi'),
                        TextEntry::make('alamat')
                            ->label('Alamat Lengkap')
                            ->placeholder('Belum diisi')
                            ->columnSpanFull(),
                    ]),

                // =============================================
                // SECTION 3: Foto KTP
                // =============================================
                Section::make('Foto KTP')
                    ->icon('heroicon-o-camera')
                    ->schema([
                        ImageEntry::make('foto_ktp_url')
                            ->label('Dokumen KTP')
                            ->disk('public')
                            ->height(250)
                            ->extraImgAttributes(['class' => 'rounded-xl border shadow object-contain'])
                            ->placeholder('Belum ada foto KTP yang diunggah')
                            ->columnSpanFull(),
                    ]),

                // =============================================
                // SECTION 4: Kontak Darurat
                // =============================================
                Section::make('Kontak Darurat')
                    ->icon('heroicon-o-phone')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('kontak_darurat_nama')
                            ->label('Nama Kontak Darurat')
                            ->placeholder('Belum diisi'),
                        TextEntry::make('kontak_darurat_hp')
                            ->label('No. HP Kontak Darurat')
                            ->placeholder('Belum diisi')
                            ->copyable(),
                    ]),

                // =============================================
                // SECTION 5: Metadata
                // =============================================
                Section::make('Informasi Sistem')
                    ->icon('heroicon-o-clock')
                    ->columns(3)
                    ->collapsed()
                    ->schema([
                        TextEntry::make('email_verified_at')
                            ->label('Email Terverifikasi')
                            ->dateTime('d M Y, H:i')
                            ->placeholder('Belum diverifikasi'),
                        TextEntry::make('created_at')
                            ->label('Terdaftar Sejak')
                            ->dateTime('d M Y, H:i'),
                        TextEntry::make('updated_at')
                            ->label('Terakhir Diperbarui')
                            ->dateTime('d M Y, H:i'),
                        TextEntry::make('deleted_at')
                            ->label('Dihapus Pada')
                            ->dateTime('d M Y, H:i')
                            ->placeholder('-')
                            ->visible(fn (User $record): bool => $record->trashed()),
                    ]),

            ]);
    }
}
