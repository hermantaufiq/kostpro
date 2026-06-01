<?php

namespace App\Filament\Resources\Penyewaans\Tables;

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

class PenyewaansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->searchable(),
                TextColumn::make('kamar.id')
                    ->searchable(),
                TextColumn::make('kode_penyewaan')
                    ->searchable(),
                TextColumn::make('tanggal_masuk')
                    ->date()
                    ->sortable(),
                TextColumn::make('tanggal_keluar')
                    ->date()
                    ->sortable(),
                TextColumn::make('durasi_bulan')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge(),
                TextColumn::make('approved_by')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('tanggal_approval')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('ktp_url')
                    ->searchable(),
                TextColumn::make('ktp_path')
                    ->searchable(),
                TextColumn::make('kontrak_url')
                    ->searchable(),
                TextColumn::make('harga_bulanan_snapshot')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('deposit_amount')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('deposit_paid')
                    ->boolean(),
                TextColumn::make('deposit_paid_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('checkin_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('checkout_at')
                    ->dateTime()
                    ->sortable(),
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
