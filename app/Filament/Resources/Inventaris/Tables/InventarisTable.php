<?php

namespace App\Filament\Resources\Inventaris\Tables;

use App\Enums\KondisiInventaris;
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

class InventarisTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_barang')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('kode_barang')
                    ->searchable()
                    ->copyable()
                    ->placeholder('-'),
                TextColumn::make('kamar.nama')
                    ->label('Kamar')
                    ->searchable()
                    ->sortable()
                    ->placeholder('Fasilitas Umum'),
                TextColumn::make('kondisi')
                    ->badge()
                    ->sortable(),
                TextColumn::make('harga_beli')
                    ->money('idr')
                    ->sortable()
                    ->placeholder('-'),
                TextColumn::make('tanggal_beli')
                    ->date()
                    ->sortable()
                    ->placeholder('-'),
                ImageColumn::make('foto_url')
                    ->label('Foto')
                    ->circular()
                    ->placeholder('-'),
            ])
            ->filters([
                SelectFilter::make('kondisi')
                    ->options(KondisiInventaris::class),
                SelectFilter::make('kamar_id')
                    ->relationship('kamar', 'nama')
                    ->label('Kamar'),
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
