<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class CodeGeneratorService
{
    /**
     * Generate unique kode with prefix and sequential number
     * Format: PREFIX-YYYYMM-SEQUENTIAL
     */
    public static function generate(string $prefix, ?string $table = null): string
    {
        $year = date('Y');
        $month = date('m');
        $yearMonth = $year . $month;

        $table = $table ?? strtolower($prefix);
        
        // Map table name to the correct primary key code column name
        $column = match($table) {
            'kamar' => 'kode_kamar',
            'penyewaan' => 'kode_penyewaan',
            'tagihan' => 'kode_tagihan',
            'pembayaran' => 'kode_pembayaran',
            default => 'kode_' . strtolower($prefix),
        };

        // Get last number for this month
        $lastRecord = DB::table($table)
            ->where(DB::raw("LEFT($column, 10)"), $prefix . '-' . $yearMonth)
            ->orderBy(DB::raw("CAST(RIGHT($column, 4) AS UNSIGNED)"), 'desc')
            ->first();

        if ($lastRecord) {
            $lastNum = (int)substr($lastRecord->$column, -4);
            $nextNum = str_pad($lastNum + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $nextNum = '0001';
        }

        return $prefix . '-' . $yearMonth . '-' . $nextNum;
    }

    public static function generateKamar(): string
    {
        return self::generate('KMR', 'kamar');
    }

    public static function generatePenyewaan(): string
    {
        return self::generate('PSW', 'penyewaan');
    }

    public static function generateTagihan(): string
    {
        return self::generate('TGH', 'tagihan');
    }

    public static function generatePembayaran(): string
    {
        return self::generate('PMT', 'pembayaran');
    }
}
