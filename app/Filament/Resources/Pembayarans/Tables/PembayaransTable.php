<?php

namespace App\Filament\Resources\Pembayarans\Tables;

use App\Enums\MetodePembayaran;
use App\Enums\StatusPembayaran;
use App\Models\Pembayaran;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Actions\ExportBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
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
                    ->weight('bold')
                    ->copyable(),
                TextColumn::make('tagihan.no_tagihan')
                    ->label('No. Invoice')
                    ->searchable()
                    ->placeholder('-'),
                TextColumn::make('tagihan.penyewaan.penyewa.name')
                    ->label('Penyewa')
                    ->searchable()
                    ->placeholder('-'),
                TextColumn::make('metode_pembayaran')
                    ->label('Metode')
                    ->badge()
                    ->color('info'),
                TextColumn::make('jumlah_diterima')
                    ->label('Jumlah Bayar')
                    ->money('idr')
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn ($state): string => match (is_object($state) ? $state->value : $state) {
                        'pending'  => 'warning',
                        'success'  => 'success',
                        'failed'   => 'danger',
                        'expired'  => 'gray',
                        'refunded' => 'primary',
                        default    => 'gray',
                    }),
                TextColumn::make('paid_at')
                    ->label('Tgl Bayar')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->placeholder('-'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(StatusPembayaran::class),
                TrashedFilter::make(),
            ])
            ->recordActions([
                Action::make('approve_manual')
                    ->label('Verifikasi')
                    ->icon('heroicon-m-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (Pembayaran $record): bool =>
                        $record->status instanceof \BackedEnum
                            ? $record->status->value === 'pending'
                            : $record->status === 'pending'
                    )
                    ->action(function (Pembayaran $record): void {
                        $record->update([
                            'status'  => StatusPembayaran::Success,
                            'paid_at' => now(),
                        ]);
                        if ($record->tagihan) {
                            $record->tagihan->update([
                                'status'       => 'paid',
                                'tanggal_bayar' => now(),
                            ]);
                        }
                    }),
                ViewAction::make(),
                EditAction::make(),
            ])
            ->headerActions([
                ExportAction::make()
                    ->exporter(\App\Filament\Exports\PembayaranExporter::class),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                    ExportBulkAction::make()
                        ->exporter(\App\Filament\Exports\PembayaranExporter::class),
                ]),
            ]);
    }
}
