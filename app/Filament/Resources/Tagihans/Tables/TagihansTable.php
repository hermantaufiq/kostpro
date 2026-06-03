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
                TextColumn::make('kode_tagihan')
                    ->label('No. Invoice')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('user.name')
                    ->label('Penyewa')
                    ->searchable(),
                TextColumn::make('penyewaan.kode_penyewaan')
                    ->label('Booking')
                    ->searchable(),
                TextColumn::make('periode')
                    ->label('Periode')
                    ->getStateUsing(fn (\App\Models\Tagihan $record) => $record->periode_bulan . '/' . $record->periode_tahun)
                    ->sortable(),
                TextColumn::make('total_tagihan')
                    ->label('Total')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn ($state): string => match ($state instanceof \BackedEnum ? $state->value : $state) {
                        'unpaid' => 'warning',
                        'paid' => 'success',
                        'overdue' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('tanggal_jatuh_tempo')
                    ->label('Jatuh Tempo')
                    ->date('d M Y')
                    ->sortable(),
            ])
            ->filters([
                \Filament\Tables\Filters\SelectFilter::make('status')
                    ->options(\App\Enums\StatusTagihan::class),
                TrashedFilter::make(),
            ])
            ->recordActions([
                \Filament\Actions\Action::make('print')
                    ->label('Print Invoice')
                    ->icon('heroicon-m-printer')
                    ->color('info')
                    ->url(fn (\App\Models\Tagihan $record) => route('invoice.print', ['id' => $record->id]))
                    ->openUrlInNewTab(),
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                    \Filament\Actions\ExportBulkAction::make()
                        ->exporter(\App\Filament\Exports\TagihanExporter::class),
                ]),
            ])
            ->headerActions([
                \Filament\Actions\ExportAction::make()
                    ->exporter(\App\Filament\Exports\TagihanExporter::class),
            ]);
    }
}
