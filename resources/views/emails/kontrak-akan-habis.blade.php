<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #f87171; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
        .content { background-color: #ffffff; padding: 30px; border: 1px solid #e5e7eb; border-top: none; border-radius: 0 0 8px 8px; }
        .button { display: inline-block; padding: 12px 24px; background-color: #ef4444; color: white; text-decoration: none; border-radius: 6px; font-weight: bold; margin-top: 20px; }
        .footer { text-align: center; margin-top: 20px; font-size: 12px; color: #6b7280; }
        .warning-box { background-color: #fef2f2; border-left: 4px solid #ef4444; padding: 15px; margin: 20px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2 style="margin: 0;">Pengingat Kontrak Kos</h2>
        </div>
        <div class="content">
            <p>Halo <strong>{{ $penyewaan->user->name }}</strong>,</p>
            
            <p>Ini adalah pengingat otomatis bahwa kontrak sewa kos Anda untuk <strong>Kamar {{ $penyewaan->kamar->nama }}</strong> akan segera berakhir.</p>
            
            <div class="warning-box">
                <p style="margin: 0;"><strong>Sisa Waktu:</strong> {{ $daysRemaining }} Hari</p>
                <p style="margin: 5px 0 0 0;"><strong>Tanggal Berakhir:</strong> {{ \Carbon\Carbon::parse($penyewaan->tanggal_keluar)->translatedFormat('d F Y') }}</p>
            </div>
            
            <p>Jika Anda berencana untuk memperpanjang masa sewa, Anda dapat melakukannya langsung melalui portal penyewa di dashboard Anda.</p>
            
            <p style="color: #b91c1c; font-size: 0.9em;">
                <strong>Catatan Penting:</strong> Perpanjangan kontrak hanya dapat dilakukan jika Anda tidak memiliki tagihan yang belum dibayar (tunggakan).
            </p>
            
            <div style="text-align: center;">
                <a href="{{ route('dashboard') }}" class="button">Perpanjang Kontrak Sekarang</a>
            </div>
        </div>
        <div class="footer">
            <p>Email ini dikirim otomatis oleh sistem KostPro. Mohon tidak membalas email ini.</p>
        </div>
    </div>
</body>
</html>
