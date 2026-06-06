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
                TextEntry::make('luas')
                    ->placeholder('-'),
                TextEntry::make('harga_bulanan')
                    ->numeric(),
                TextEntry::make('harga_deposit')
                    ->numeric(),
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
