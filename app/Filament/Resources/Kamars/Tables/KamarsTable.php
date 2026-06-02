<?php

namespace App\Filament\Resources\Kamars\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class KamarsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\ImageColumn::make('images')
                    ->circular()
                    ->stacked()
                    ->limit(3),
                TextColumn::make('kode_kamar')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('nama')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('tipe')
                    ->badge()
                    ->color(fn ($state): string => match ($state instanceof \BackedEnum ? $state->value : $state) {
                        'standar' => 'gray',
                        'deluxe' => 'info',
                        'vip' => 'warning',
                        'suite' => 'success',
                        default => 'gray',
                    }),
                TextColumn::make('lantai')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('harga_bulanan')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn ($state): string => match ($state instanceof \BackedEnum ? $state->value : $state) {
                        'tersedia' => 'success',
                        'terisi' => 'primary',
                        'maintenance' => 'danger',
                        'reserved' => 'warning',
                        default => 'gray',
                    }),
                IconColumn::make('is_featured')
                    ->boolean()
                    ->label('Featured'),
            ])
            ->filters([
                \Filament\Tables\Filters\SelectFilter::make('status')
                    ->options(\App\Enums\StatusKamar::class),
                \Filament\Tables\Filters\SelectFilter::make('tipe')
                    ->options(\App\Enums\TipeKamar::class),
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
