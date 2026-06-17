<?php

namespace App\Filament\Resources\Kamars\Schemas;

use App\Models\Kamar;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class KamarInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('kode_kamar'),
                TextEntry::make('nama'),
                TextEntry::make('tipe')
                    ->badge(),
                TextEntry::make('gender')
                    ->label('Target Penghuni')
                    ->badge()
                    ->color(fn ($state): string => match ($state instanceof \BackedEnum ? $state->value : $state) {
                        'putra'  => 'info',
                        'putri'  => 'danger',
                        'campur' => 'success',
                        default  => 'gray',
                    }),
                TextEntry::make('lantai')
                    ->numeric(),
                TextEntry::make('kapasitas')
                    ->numeric(),
                TextEntry::make('sisa_slot')
                    ->label('Sisa Slot')
                    ->badge()
                    ->color(fn ($state) => $state > 0 ? 'success' : 'danger'),
                TextEntry::make('luas')
                    ->placeholder('-'),
                TextEntry::make('harga_bulanan')
                    ->label('Harga Normal')
                    ->money('IDR'),
                TextEntry::make('harga_efektif')
                    ->label('Harga Efektif (Tampil Publik)')
                    ->money('IDR')
                    ->color(fn (Kamar $record) => $record->isPromoAktif() ? 'success' : null),
                TextEntry::make('harga_promo')
                    ->label('Harga Promo')
                    ->money('IDR')
                    ->placeholder('-')
                    ->visible(fn (Kamar $record) => $record->promo_aktif),
                TextEntry::make('label_promo')
                    ->label('Label Promo')
                    ->placeholder('-')
                    ->visible(fn (Kamar $record) => $record->promo_aktif),
                TextEntry::make('promo_mulai')
                    ->label('Promo Mulai')
                    ->date('d M Y')
                    ->placeholder('Langsung aktif')
                    ->visible(fn (Kamar $record) => $record->promo_aktif),
                TextEntry::make('promo_selesai')
                    ->label('Promo Selesai')
                    ->date('d M Y')
                    ->placeholder('Tanpa batas')
                    ->visible(fn (Kamar $record) => $record->promo_aktif),
                TextEntry::make('harga_deposit')
                    ->money('IDR'),
                TextEntry::make('deskripsi')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('status')
                    ->badge(),
                IconEntry::make('is_featured')
                    ->boolean(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Kamar $record): bool => $record->trashed()),
            ]);
    }
}
