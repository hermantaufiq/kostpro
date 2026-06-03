<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Pages\Page;
use Filament\Notifications\Notification;

class SettingsPage extends Page
{
    protected string $view = 'filament.pages.settings-page';

    public ?array $data = [];

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-cog-6-tooth';
    }

    public static function getNavigationLabel(): string
    {
        return 'Pengaturan Kos';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Sistem';
    }

    public static function getNavigationSort(): ?int
    {
        return 10;
    }

    public function getTitle(): string
    {
        return 'Pengaturan Kos';
    }

    public static function canAccess(): bool
    {
        return auth()->user()->can('view_any_settings');
    }

    public function mount(): void
    {
        $settings = Setting::pluck('value', 'key')->toArray();

        $this->form->fill([
            'nama_kos' => $settings['nama_kos'] ?? 'KosPro',
            'alamat_kos' => $settings['alamat_kos'] ?? '',
            'rekening_pembayaran' => $settings['rekening_pembayaran'] ?? '',
            'kontak_admin' => $settings['kontak_admin'] ?? '',
            'nominal_denda' => $settings['nominal_denda'] ?? '0',
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Informasi Umum')
                    ->schema([
                        TextInput::make('nama_kos')
                            ->label('Nama Kos')
                            ->required(),
                        Textarea::make('alamat_kos')
                            ->label('Alamat Lengkap Kos')
                            ->rows(3),
                        TextInput::make('kontak_admin')
                            ->label('Kontak Admin (WhatsApp)')
                            ->placeholder('081234567890'),
                    ]),
                Section::make('Keuangan')
                    ->schema([
                        Textarea::make('rekening_pembayaran')
                            ->label('Informasi Rekening Pembayaran')
                            ->placeholder("BCA 1234567890 a.n Admin\nMandiri 0987654321 a.n Admin")
                            ->rows(4),
                        TextInput::make('nominal_denda')
                            ->label('Nominal Denda Keterlambatan per Hari')
                            ->numeric()
                            ->prefix('Rp')
                            ->default(0),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        foreach ($data as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        Notification::make()
            ->title('Pengaturan berhasil disimpan')
            ->success()
            ->send();
    }
}
