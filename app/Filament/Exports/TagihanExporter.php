<?php

namespace App\Filament\Exports;

use App\Models\Tagihan;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

class TagihanExporter extends Exporter
{
    protected static ?string $model = Tagihan::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('penyewaan.id'),
            ExportColumn::make('user.name'),
            ExportColumn::make('kode_tagihan'),
            ExportColumn::make('periode_bulan'),
            ExportColumn::make('periode_tahun'),
            ExportColumn::make('jumlah_tagihan'),
            ExportColumn::make('jumlah_denda'),
            ExportColumn::make('total_tagihan'),
            ExportColumn::make('status'),
            ExportColumn::make('tanggal_tagihan'),
            ExportColumn::make('tanggal_jatuh_tempo'),
            ExportColumn::make('tanggal_bayar'),
            ExportColumn::make('reminder_count'),
            ExportColumn::make('last_reminder_at'),
            ExportColumn::make('catatan'),
            ExportColumn::make('is_auto_generated'),
            ExportColumn::make('created_at'),
            ExportColumn::make('updated_at'),
            ExportColumn::make('deleted_at'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your tagihan export has completed and ' . Number::format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
