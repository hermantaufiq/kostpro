<?php

namespace App\Filament\Exports;

use App\Models\Pembayaran;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

class PembayaranExporter extends Exporter
{
    protected static ?string $model = Pembayaran::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('tagihan.id'),
            ExportColumn::make('user.name'),
            ExportColumn::make('kode_pembayaran'),
            ExportColumn::make('xendit_invoice_id'),
            ExportColumn::make('xendit_external_id'),
            ExportColumn::make('xendit_payment_url'),
            ExportColumn::make('metode'),
            ExportColumn::make('channel_code'),
            ExportColumn::make('jumlah'),
            ExportColumn::make('biaya_admin'),
            ExportColumn::make('jumlah_diterima'),
            ExportColumn::make('status'),
            ExportColumn::make('payload_request'),
            ExportColumn::make('payload_response'),
            ExportColumn::make('payload_webhook'),
            ExportColumn::make('paid_at'),
            ExportColumn::make('expired_at'),
            ExportColumn::make('created_at'),
            ExportColumn::make('updated_at'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your pembayaran export has completed and ' . Number::format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
