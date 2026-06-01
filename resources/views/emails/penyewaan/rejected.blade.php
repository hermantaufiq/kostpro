<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Status Pengajuan Sewa</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f8fafc; padding: 40px; color: #334155;">
    <div style="max-w: 600px; margin: 0 auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
        <div style="background-color: #ef4444; padding: 24px; text-align: center;">
            <h1 style="color: #ffffff; margin: 0; font-size: 24px;">Status Pengajuan Sewa</h1>
        </div>
        <div style="padding: 32px;">
            <p>Halo <strong>{{ $penyewaan->user->name }}</strong>,</p>
            <p>Mohon maaf, pengajuan sewa Anda untuk kamar <strong>{{ $penyewaan->kamar->nama }}</strong> saat ini tidak dapat kami setujui.</p>
            
            @if($penyewaan->catatan_admin)
            <div style="background-color: #fef2f2; border-left: 4px solid #ef4444; padding: 16px; margin: 24px 0; border-radius: 4px;">
                <p style="color: #991b1b; margin: 0;"><strong>Alasan Penolakan:</strong><br>{{ $penyewaan->catatan_admin }}</p>
            </div>
            @endif

            <p>Anda dapat mencari dan mengajukan sewa untuk kamar lainnya yang tersedia di KosPro.</p>
            
            <div style="text-align: center; margin: 32px 0;">
                <a href="{{ route('kamar.index') }}" style="background-color: #4f46e5; color: #ffffff; padding: 12px 24px; text-decoration: none; border-radius: 8px; font-weight: bold; display: inline-block;">Cari Kamar Lain</a>
            </div>
            
            <p style="font-size: 14px; color: #64748b; margin-top: 32px; border-top: 1px solid #e2e8f0; padding-top: 16px;">
                Jika Anda memiliki pertanyaan, silakan hubungi admin kos kami.
                <br><br>Salam hangat,<br><strong>Tim KosPro</strong>
            </p>
        </div>
    </div>
</body>
</html>
