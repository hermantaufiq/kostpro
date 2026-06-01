<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Penyewaan Disetujui</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f8fafc; padding: 40px; color: #334155;">
    <div style="max-w: 600px; margin: 0 auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
        <div style="background-color: #4f46e5; padding: 24px; text-align: center;">
            <h1 style="color: #ffffff; margin: 0; font-size: 24px;">Penyewaan Disetujui! 🎉</h1>
        </div>
        <div style="padding: 32px;">
            <p>Halo <strong>{{ $penyewaan->user->name }}</strong>,</p>
            <p>Selamat! Pengajuan sewa Anda untuk kamar <strong>{{ $penyewaan->kamar->nama }}</strong> telah disetujui oleh admin.</p>
            
            <div style="background-color: #f1f5f9; padding: 16px; border-radius: 8px; margin: 24px 0;">
                <p style="margin: 0 0 8px 0;"><strong>Kode Penyewaan:</strong> {{ $penyewaan->kode_penyewaan }}</p>
                <p style="margin: 0 0 8px 0;"><strong>Tanggal Masuk:</strong> {{ $penyewaan->tanggal_masuk->format('d M Y') }}</p>
                <p style="margin: 0;"><strong>Durasi:</strong> {{ $penyewaan->durasi_bulan }} Bulan</p>
            </div>

            <p>Untuk mengaktifkan penyewaan ini, silakan lakukan pembayaran untuk <strong>Tagihan Bulan Pertama</strong> beserta <strong>Uang Deposit</strong>.</p>
            
            <div style="text-align: center; margin: 32px 0;">
                <a href="{{ route('user.tagihan.index') }}" style="background-color: #4f46e5; color: #ffffff; padding: 12px 24px; text-decoration: none; border-radius: 8px; font-weight: bold; display: inline-block;">Lihat Tagihan Saya</a>
            </div>
            
            <p style="font-size: 14px; color: #64748b; margin-top: 32px; border-top: 1px solid #e2e8f0; padding-top: 16px;">
                Jika Anda memiliki pertanyaan, silakan balas email ini atau hubungi admin kos.
                <br><br>Salam hangat,<br><strong>Tim KosPro</strong>
            </p>
        </div>
    </div>
</body>
</html>
