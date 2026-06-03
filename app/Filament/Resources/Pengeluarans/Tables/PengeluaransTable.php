<?php

namespace App\Filament\Resources\Pengeluarans\Tables;

use App\Enums\KategoriPengeluaran;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class PengeluaransTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tanggal')
                    ->date()
                    ->sortable(),
                TextColumn::make('kategori')
                    ->badge()
                    ->sortable(),
                TextColumn::make('jumlah')
                    ->money('idr')
                    ->sortable(),
                TextColumn::make('keterangan')
                    ->searchable()
                    ->limit(50),
                TextColumn::make('creator.name')
                    ->label('Dibuat Oleh')
                    ->sortable(),
                ImageColumn::make('bukti_url')
                    ->label('Bukti')
                    ->circular(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('kategori')
                    ->options(KategoriPengeluaran::class),
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
                    \Filament\Tables\Actions\ExportBulkAction::make()
                        ->exporter(\App\Filament\Exports\PengeluaranExporter::class),
                ]),
            ])
            ->headerActions([
                \Filament\Tables\Actions\ExportAction::make()
                    ->exporter(\App\Filament\Exports\PengeluaranExporter::class),
            ]);
    }
}
