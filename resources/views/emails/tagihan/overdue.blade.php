<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Tagihan Overdue</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f8fafc; padding: 40px; color: #334155;">
    <div style="max-w: 600px; margin: 0 auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
        <div style="background-color: #dc2626; padding: 24px; text-align: center;">
            <h1 style="color: #ffffff; margin: 0; font-size: 24px;">PEMBERITAHUAN: Tagihan Terlambat 🚨</h1>
        </div>
        <div style="padding: 32px;">
            <p>Halo <strong>{{ $tagihan->user->name }}</strong>,</p>
            <p>Sistem kami mencatat bahwa tagihan kos Anda untuk kamar <strong>{{ $tagihan->penyewaan->kamar->nama }}</strong> telah melewati batas waktu pembayaran (jatuh tempo).</p>
            
            <div style="background-color: #fef2f2; padding: 16px; border-radius: 8px; margin: 24px 0; border: 1px solid #fecaca;">
                <p style="margin: 0 0 8px 0;"><strong>Kode Tagihan:</strong> {{ $tagihan->kode_tagihan }}</p>
                <p style="margin: 0 0 8px 0;"><strong>Jatuh Tempo:</strong> <span style="color: #991b1b; font-weight: bold; text-decoration: line-through;">{{ $tagihan->tanggal_jatuh_tempo->format('d M Y') }}</span></p>
                <p style="margin: 0 0 8px 0;"><strong>Denda Keterlambatan:</strong> <span style="color: #dc2626; font-weight: bold;">Rp {{ number_format($tagihan->jumlah_denda, 0, ',', '.') }}</span></p>
                <p style="margin: 0;"><strong>Total Harus Dibayar:</strong> <span style="font-size: 18px; font-weight: bold;">Rp {{ number_format($tagihan->total_tagihan, 0, ',', '.') }}</span></p>
            </div>

            <p style="color: #dc2626; font-weight: bold;">Mohon segera lunasi tagihan Anda beserta denda keterlambatannya.</p>
            
            <div style="text-align: center; margin: 32px 0;">
                <a href="{{ route('user.tagihan.show', $tagihan->id) }}" style="background-color: #dc2626; color: #ffffff; padding: 12px 24px; text-decoration: none; border-radius: 8px; font-weight: bold; display: inline-block;">Bayar Tagihan Sekarang</a>
            </div>
            
            <p style="font-size: 14px; color: #64748b; margin-top: 32px; border-top: 1px solid #e2e8f0; padding-top: 16px;">
                Jika Anda terus mengabaikan peringatan ini, akses ke fasilitas kos dapat ditangguhkan sementara.
                <br><br>Salam hangat,<br><strong>Tim KosPro</strong>
            </p>
        </div>
    </div>
</body>
</html>
