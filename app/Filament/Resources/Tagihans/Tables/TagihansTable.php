<?php

namespace App\Filament\Resources\Tagihans\Tables;

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

class TagihansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('penyewaan.id')
                    ->searchable(),
                TextColumn::make('user.name')
                    ->searchable(),
                TextColumn::make('kode_tagihan')
                    ->searchable(),
                TextColumn::make('periode_bulan')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('periode_tahun')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('jumlah_tagihan')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('jumlah_denda')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('total_tagihan')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge(),
                TextColumn::make('tanggal_tagihan')
                    ->date()
                    ->sortable(),
                TextColumn::make('tanggal_jatuh_tempo')
                    ->date()
                    ->sortable(),
                TextColumn::make('tanggal_bayar')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('reminder_count')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('last_reminder_at')
                    ->dateTime()
                    ->sortable(),
                IconColumn::make('is_auto_generated')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
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
