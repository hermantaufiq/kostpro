<?php

namespace App\Filament\Resources\Penyewaans\Tables;

use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ForceDeleteBulkAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Tables\Actions\ViewAction;
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
                TextColumn::make('kode_penyewaan')
                    ->label('Kode Booking')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('user.name')
                    ->label('Penyewa')
                    ->searchable(),
                TextColumn::make('kamar.nama')
                    ->label('Kamar')
                    ->searchable(),
                TextColumn::make('tanggal_masuk')
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('durasi_bulan')
                    ->label('Durasi (Bulan)')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('harga_bulanan_snapshot')
                    ->label('Harga')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn ($state): string => match ($state instanceof \BackedEnum ? $state->value : $state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'active' => 'primary',
                        'rejected' => 'danger',
                        default => 'gray',
                    }),
                IconColumn::make('deposit_paid')
                    ->label('Deposit')
                    ->boolean(),
            ])
            ->filters([
                \Filament\Tables\Filters\SelectFilter::make('status')
                    ->options(\App\Enums\StatusPenyewaan::class),
                TrashedFilter::make(),
            ])
            ->recordActions([
                \Filament\Tables\Actions\Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-m-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (\App\Models\Penyewaan $record) => $record->status->value === 'pending')
                    ->action(function (\App\Models\Penyewaan $record) {
                        $record->update([
                            'status' => 'approved',
                            'approved_by' => auth()->id(),
                            'tanggal_approval' => now(),
                        ]);
                    }),
                \Filament\Tables\Actions\Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-m-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn (\App\Models\Penyewaan $record) => $record->status->value === 'pending')
                    ->action(function (\App\Models\Penyewaan $record) {
                        $record->update([
                            'status' => 'rejected',
                            'approved_by' => auth()->id(),
                            'tanggal_approval' => now(),
                        ]);
                    }),
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
