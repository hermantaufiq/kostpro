<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Pembayaran Berhasil</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f8fafc; padding: 40px; color: #334155;">
    <div style="max-w: 600px; margin: 0 auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
        <div style="background-color: #10b981; padding: 24px; text-align: center;">
            <h1 style="color: #ffffff; margin: 0; font-size: 24px;">Pembayaran Berhasil! ✅</h1>
        </div>
        <div style="padding: 32px;">
            <p>Halo <strong>{{ $tagihan->user->name }}</strong>,</p>
            <p>Terima kasih! Pembayaran Anda untuk tagihan <strong>{{ $tagihan->kode_tagihan }}</strong> telah kami terima.</p>
            
            <div style="background-color: #f0fdf4; padding: 16px; border-radius: 8px; margin: 24px 0; border: 1px solid #bbf7d0;">
                <p style="margin: 0 0 8px 0;"><strong>Kode Tagihan:</strong> {{ $tagihan->kode_tagihan }}</p>
                <p style="margin: 0 0 8px 0;"><strong>Kamar:</strong> {{ $tagihan->penyewaan->kamar->nama }}</p>
                <p style="margin: 0 0 8px 0;"><strong>Jumlah Dibayar:</strong> Rp {{ number_format($tagihan->total_tagihan, 0, ',', '.') }}</p>
                <p style="margin: 0;"><strong>Waktu Bayar:</strong> {{ now()->format('d M Y H:i') }}</p>
            </div>
            
            <div style="text-align: center; margin: 32px 0;">
                <a href="{{ route('user.tagihan.show', $tagihan->id) }}" style="background-color: #10b981; color: #ffffff; padding: 12px 24px; text-decoration: none; border-radius: 8px; font-weight: bold; display: inline-block;">Lihat Bukti Lunas</a>
            </div>
            
            <p style="font-size: 14px; color: #64748b; margin-top: 32px; border-top: 1px solid #e2e8f0; padding-top: 16px;">
                Terima kasih telah memilih KosPro.
                <br><br>Salam hangat,<br><strong>Tim KosPro</strong>
            </p>
        </div>
    </div>
</body>
</html>
