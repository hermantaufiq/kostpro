<?php

namespace App\Filament\Resources\Kamars\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
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
                TextColumn::make('gender')
                    ->label('Penghuni')
                    ->badge()
                    ->color(fn ($state): string => match ($state instanceof \BackedEnum ? $state->value : $state) {
                        'putra'  => 'info',
                        'putri'  => 'danger',
                        'campur' => 'success',
                        default  => 'gray',
                    }),
                TextColumn::make('lantai')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('kapasitas')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('sisa_slot')
                    ->label('Sisa Slot')
                    ->badge()
                    ->color(fn ($state) => $state > 0 ? 'success' : 'danger')
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
                IconColumn::make('show_to_public')
                    ->boolean()
                    ->label('Tampil Publik')
                    ->trueIcon('heroicon-o-eye')
                    ->falseIcon('heroicon-o-eye-slash')
                    ->trueColor('success')
                    ->falseColor('danger'),
            ])
            ->filters([
                \Filament\Tables\Filters\SelectFilter::make('status')
                    ->options(\App\Enums\StatusKamar::class),
                \Filament\Tables\Filters\SelectFilter::make('tipe')
                    ->options(\App\Enums\TipeKamar::class),
                \Filament\Tables\Filters\SelectFilter::make('gender')
                    ->label('Target Penghuni')
                    ->options(\App\Enums\GenderKamar::class),
                \Filament\Tables\Filters\TernaryFilter::make('show_to_public')
                    ->label('Tampil ke Pengguna')
                    ->trueLabel('Ya — Ditampilkan')
                    ->falseLabel('Tidak — Disembunyikan'),
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
                RestoreAction::make(),
                ForceDeleteAction::make()
                    ->label('Hapus Permanen'),
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
