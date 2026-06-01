<?php

namespace App\Filament\Resources\Notifikasis\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class NotifikasisTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Penyewa')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('judul')
                    ->label('Judul')
                    ->searchable()
                    ->limit(40)
                    ->weight('bold'),
                TextColumn::make('tipe')
                    ->badge()
                    ->color(fn ($state): string => match ($state instanceof \BackedEnum ? $state->value : $state) {
                        'tagihan' => 'warning',
                        'pembayaran' => 'success',
                        'penyewaan' => 'info',
                        'sistem' => 'gray',
                        default => 'gray',
                    }),
                TextColumn::make('read_at')
                    ->label('Dibaca')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->placeholder('Belum dibaca')
                    ->color(fn ($state) => $state ? 'success' : 'warning'),
                TextColumn::make('created_at')
                    ->label('Dikirim')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                \Filament\Tables\Filters\SelectFilter::make('tipe')
                    ->options(\App\Enums\TipeNotifikasi::class),
                \Filament\Tables\Filters\TernaryFilter::make('read_at')
                    ->label('Sudah Dibaca')
                    ->nullable(),
            ])
            ->recordActions([
                \Filament\Tables\Actions\Action::make('mark_read')
                    ->label('Tandai Dibaca')
                    ->icon('heroicon-m-eye')
                    ->color('success')
                    ->visible(fn (\App\Models\Notifikasi $record) => is_null($record->read_at))
                    ->action(fn (\App\Models\Notifikasi $record) => $record->update(['read_at' => now()])),
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
