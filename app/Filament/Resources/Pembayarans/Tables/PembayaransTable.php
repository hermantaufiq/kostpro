<?php

namespace App\Filament\Resources\Pembayarans\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PembayaransTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode_pembayaran')
                    ->label('Kode Bayar')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('tagihan.kode_tagihan')
                    ->label('No. Invoice')
                    ->searchable(),
                TextColumn::make('user.name')
                    ->label('Penyewa')
                    ->searchable(),
                TextColumn::make('metode')
                    ->label('Metode')
                    ->badge()
                    ->color('info'),
                TextColumn::make('channel_code')
                    ->label('Channel')
                    ->searchable(),
                TextColumn::make('jumlah')
                    ->label('Jumlah Bayar')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn ($state): string => match ($state instanceof \BackedEnum ? $state->value : $state) {
                        'pending' => 'warning',
                        'success' => 'success',
                        'failed' => 'danger',
                        'expired' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('paid_at')
                    ->label('Tgl Bayar')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                \Filament\Tables\Filters\SelectFilter::make('status')
                    ->options(\App\Enums\StatusPembayaran::class),
                \Filament\Tables\Filters\SelectFilter::make('metode')
                    ->options(\App\Enums\MetodePembayaran::class),
            ])
            ->recordActions([
                \Filament\Tables\Actions\Action::make('approve_manual')
                    ->label('Verifikasi Manual')
                    ->icon('heroicon-m-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (\App\Models\Pembayaran $record) => $record->status->value === 'pending' && $record->metode->value === 'manual')
                    ->action(function (\App\Models\Pembayaran $record) {
                        // Approve manual payment
                        $record->update([
                            'status' => 'success',
                            'paid_at' => now(),
                        ]);
                        
                        // Update tagihan
                        $record->tagihan->update([
                            'status' => 'paid',
                            'tanggal_bayar' => now(),
                        ]);
                    }),
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
