<?php

namespace App\Filament\Resources\Keluhans\Schemas;

use App\Models\Keluhan;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class KeluhanInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('user.name')
                    ->label('User'),
                TextEntry::make('kamar.id')
                    ->label('Kamar')
                    ->placeholder('-'),
                TextEntry::make('judul'),
                TextEntry::make('deskripsi')
                    ->columnSpanFull(),
                TextEntry::make('status')
                    ->badge(),
                TextEntry::make('prioritas')
                    ->badge(),
                TextEntry::make('foto_url')
                    ->placeholder('-'),
                TextEntry::make('tanggapan_admin')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('resolved_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Keluhan $record): bool => $record->trashed()),
            ]);
    }
}
