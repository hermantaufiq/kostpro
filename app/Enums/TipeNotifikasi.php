<?php

namespace App\Enums;

enum TipeNotifikasi: string
{
    case TagihanBaru      = 'tagihan_baru';
    case TagihanReminder  = 'tagihan_reminder';
    case TagihanOverdue   = 'tagihan_overdue';
    case PembayaranSucces = 'pembayaran_success';
    case PenyewaanApproved = 'penyewaan_approved';
    case PenyewaanRejected = 'penyewaan_rejected';
    case PenyewaanExpired  = 'penyewaan_expired';
    case KontrakAkanHabis     = 'kontrak_akan_habis';      // Reminder H-30/14/7
    case KontrakDiperpanjang  = 'kontrak_diperpanjang';    // Konfirmasi ke penyewa
    case KontrakDiperpanjangAdmin = 'kontrak_diperpanjang_admin'; // Notif ke admin
    case Sistem           = 'sistem';

    public function label(): string
    {
        return match($this) {
            self::TagihanBaru             => 'Tagihan Baru',
            self::TagihanReminder         => 'Reminder Tagihan',
            self::TagihanOverdue          => 'Tagihan Jatuh Tempo',
            self::PembayaranSucces        => 'Pembayaran Berhasil',
            self::PenyewaanApproved       => 'Penyewaan Disetujui',
            self::PenyewaanRejected       => 'Penyewaan Ditolak',
            self::PenyewaanExpired        => 'Kontrak Berakhir',
            self::KontrakAkanHabis        => 'Kontrak Akan Berakhir',
            self::KontrakDiperpanjang     => 'Kontrak Diperpanjang',
            self::KontrakDiperpanjangAdmin => 'Perpanjangan Kontrak Baru',
            self::Sistem                  => 'Informasi Sistem',
        };
    }

    public function icon(): string
    {
        return match($this) {
            self::TagihanBaru             => '📄',
            self::TagihanReminder         => '⏰',
            self::TagihanOverdue          => '🔴',
            self::PembayaranSucces        => '✅',
            self::PenyewaanApproved       => '🎉',
            self::PenyewaanRejected       => '❌',
            self::PenyewaanExpired        => '📅',
            self::KontrakAkanHabis        => '⚠️',
            self::KontrakDiperpanjang     => '🔄',
            self::KontrakDiperpanjangAdmin => '🔄',
            self::Sistem                  => 'ℹ️',
        };
    }

    public function colorClass(): string
    {
        return match($this) {
            self::TagihanBaru             => 'bg-indigo-100 text-indigo-600',
            self::TagihanReminder         => 'bg-amber-100 text-amber-600',
            self::TagihanOverdue          => 'bg-red-100 text-red-600',
            self::PembayaranSucces        => 'bg-emerald-100 text-emerald-600',
            self::PenyewaanApproved       => 'bg-emerald-100 text-emerald-600',
            self::PenyewaanRejected       => 'bg-red-100 text-red-600',
            self::PenyewaanExpired        => 'bg-slate-100 text-slate-600',
            self::KontrakAkanHabis        => 'bg-orange-100 text-orange-600',
            self::KontrakDiperpanjang     => 'bg-blue-100 text-blue-600',
            self::KontrakDiperpanjangAdmin => 'bg-blue-100 text-blue-600',
            self::Sistem                  => 'bg-blue-100 text-blue-600',
        };
    }
}
