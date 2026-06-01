<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reminder Tagihan</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f8fafc; padding: 40px; color: #334155;">
    <div style="max-w: 600px; margin: 0 auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
        <div style="background-color: #f59e0b; padding: 24px; text-align: center;">
            <h1 style="color: #ffffff; margin: 0; font-size: 24px;">Reminder Tagihan Kos ⚠️</h1>
        </div>
        <div style="padding: 32px;">
            <p>Halo <strong>{{ $tagihan->user->name }}</strong>,</p>
            <p>Ini adalah pengingat ramah bahwa tagihan kos Anda untuk kamar <strong>{{ $tagihan->penyewaan->kamar->nama }}</strong> akan segera jatuh tempo.</p>
            
            <div style="background-color: #fffbeb; padding: 16px; border-radius: 8px; margin: 24px 0; border: 1px solid #fde68a;">
                <p style="margin: 0 0 8px 0;"><strong>Kode Tagihan:</strong> {{ $tagihan->kode_tagihan }}</p>
                <p style="margin: 0 0 8px 0;"><strong>Jatuh Tempo:</strong> <span style="color: #b45309; font-weight: bold;">{{ $tagihan->tanggal_jatuh_tempo->format('d M Y') }}</span></p>
                <p style="margin: 0;"><strong>Total Tagihan:</strong> Rp {{ number_format($tagihan->total_tagihan, 0, ',', '.') }}</p>
            </div>

            <p>Mohon segera lakukan pembayaran sebelum tanggal jatuh tempo untuk menghindari denda keterlambatan.</p>
            
            <div style="text-align: center; margin: 32px 0;">
                <a href="{{ route('user.tagihan.show', $tagihan->id) }}" style="background-color: #f59e0b; color: #ffffff; padding: 12px 24px; text-decoration: none; border-radius: 8px; font-weight: bold; display: inline-block;">Bayar Sekarang</a>
            </div>
            
            <p style="font-size: 14px; color: #64748b; margin-top: 32px; border-top: 1px solid #e2e8f0; padding-top: 16px;">
                Jika Anda sudah melakukan pembayaran, silakan abaikan email ini.
                <br><br>Salam hangat,<br><strong>Tim KosPro</strong>
            </p>
        </div>
    </div>
</body>
</html>
