<?php

namespace App\Filament\Resources\Kamars\Schemas;

use App\Enums\StatusKamar;
use App\Enums\TipeKamar;
use App\Enums\GenderKamar;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class KamarForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Section::make('Informasi Dasar')
                    ->schema([
                        TextInput::make('kode_kamar')
                            ->required()
                            ->unique(ignoreRecord: true),
                        TextInput::make('nama')
                            ->required(),
                        Select::make('tipe')
                            ->options(TipeKamar::class)
                            ->default('standar')
                            ->required(),
                        Select::make('gender')
                            ->label('Target Penghuni')
                            ->options(GenderKamar::class)
                            ->default('campur')
                            ->required()
                            ->helperText('Putra = khusus pria, Putri = khusus wanita, Campur = semua gender'),
                        Select::make('status')
                            ->options(StatusKamar::class)
                            ->default('tersedia')
                            ->required()
                            ->live(),
                        TextInput::make('kapasitas')
                            ->label('Kapasitas Kamar (Orang)')
                            ->numeric()
                            ->default(1)
                            ->minValue(1)
                            ->required(),
                    ])->columns(2),

                \Filament\Schemas\Components\Section::make('Detail Ruangan & Fasilitas')
                    ->schema([
                        TextInput::make('lantai')
                            ->required()
                            ->numeric()
                            ->default(1),
                        TextInput::make('luas')
                            ->placeholder('Contoh: 4x5'),
                        \Filament\Forms\Components\TagsInput::make('fasilitas')
                            ->columnSpanFull()
                            ->placeholder('Tambah fasilitas lalu tekan enter')
                            ->suggestions(['AC', 'Smart TV', 'Kasur Springbed', 'Lemari', 'Kamar Mandi Dalam', 'Water Heater', 'Meja Belajar', 'WiFi', 'Kipas Angin', 'Kulkas Mini', 'Jendela Luar', 'Sofa']),
                    ])->columns(2),

                \Filament\Schemas\Components\Section::make('Harga')
                    ->schema([
                        TextInput::make('harga_bulanan')
                            ->label('Harga Normal / Bulan')
                            ->required()
                            ->numeric()
                            ->prefix('Rp'),
                        TextInput::make('harga_deposit')
                            ->required()
                            ->numeric()
                            ->default(0)
                            ->prefix('Rp'),
                    ])->columns(2),

                \Filament\Schemas\Components\Section::make('Promo Harga (seperti Agoda/Traveloka)')
                    ->description('Atur harga promo yang tampil di halaman publik. Harga normal dicoret, harga promo ditampilkan menonjol.')
                    ->schema([
                        Toggle::make('promo_aktif')
                            ->label('Aktifkan Promo')
                            ->default(false)
                            ->live(),
                        TextInput::make('harga_promo')
                            ->label('Harga Promo / Bulan')
                            ->numeric()
                            ->prefix('Rp')
                            ->visible(fn ($get) => $get('promo_aktif'))
                            ->required(fn ($get) => $get('promo_aktif'))
                            ->lt('harga_bulanan')
                            ->helperText('Harus lebih murah dari harga normal'),
                        TextInput::make('label_promo')
                            ->label('Label Promo')
                            ->placeholder('Contoh: Promo Awal Tahun')
                            ->maxLength(50)
                            ->visible(fn ($get) => $get('promo_aktif')),
                        DatePicker::make('promo_mulai')
                            ->label('Mulai Promo')
                            ->visible(fn ($get) => $get('promo_aktif'))
                            ->helperText('Kosongkan = langsung aktif'),
                        DatePicker::make('promo_selesai')
                            ->label('Selesai Promo')
                            ->visible(fn ($get) => $get('promo_aktif'))
                            ->afterOrEqual('promo_mulai')
                            ->helperText('Kosongkan = tanpa batas waktu'),
                    ])->columns(2),

                \Filament\Schemas\Components\Section::make('Visibilitas & Publikasi')
                    ->description('Atur apakah kamar ini ditampilkan kepada calon penghuni.')
                    ->schema([
                        Toggle::make('show_to_public')
                            ->label('Tampilkan kamar ini kepada pengguna')
                            ->helperText('Jika dinonaktifkan, kamar tidak akan muncul di halaman pencarian maupun daftar kamar untuk pengguna. Berguna saat kamar sedang maintenance dan tidak ingin ditampilkan.')
                            ->default(true)
                            ->onColor('success')
                            ->offColor('danger'),
                        Toggle::make('is_featured')
                            ->label('Tampilkan di halaman utama')
                            ->default(false),
                    ])->columns(2),

                \Filament\Schemas\Components\Section::make('Media & Deskripsi')
                    ->schema([
                        \Filament\Forms\Components\RichEditor::make('deskripsi')
                            ->columnSpanFull(),
                        \Filament\Forms\Components\FileUpload::make('images')
                            ->label('Foto Galeri (Biasa)')
                            ->multiple()
                            ->image()
                            ->maxFiles(5)
                            ->directory('kamar-images')
                            ->columnSpanFull(),
                        \Filament\Forms\Components\FileUpload::make('foto_360')
                            ->label('Foto Panorama 360° (Opsional)')
                            ->image()
                            ->directory('kamar-360')
                            ->helperText('Upload foto panorama 360 derajat untuk mengaktifkan fitur Virtual Tour 360.')
                            ->columnSpanFull(),
                    ]),

                \Filament\Schemas\Components\Section::make('Kustomisasi Layout 3D')
                    ->description('Sesuaikan warna untuk fitur Layout 3D agar mendekati warna asli kamar.')
                    ->schema([
                        \Filament\Forms\Components\ColorPicker::make('warna_dinding')
                            ->label('Warna Dinding')
                            ->default('#e8f4f8'),
                        \Filament\Forms\Components\ColorPicker::make('warna_lantai')
                            ->label('Warna Lantai')
                            ->default('#8b5a2b'),
                        \Filament\Forms\Components\ColorPicker::make('warna_kasur')
                            ->label('Warna Sprei Kasur')
                            ->default('#ffffff'),
                    ])->columns(3),
            ]);
    }
}
