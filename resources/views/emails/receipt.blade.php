<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Kuitansi Pembayaran Lunas</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="text-align: center; margin-bottom: 30px;">
        <h2>{{ \App\Models\Setting::get('nama_kos', 'KosPro') }}</h2>
        <p style="color: #666;">{{ \App\Models\Setting::get('alamat_kos', '') }}</p>
    </div>

    <div style="background-color: #ecfdf5; border: 1px solid #a7f3d0; padding: 20px; border-radius: 8px; margin-bottom: 20px; text-align: center;">
        <h1 style="margin: 0; color: #059669; font-size: 24px;">LUNAS</h1>
        <p style="margin-top: 5px; color: #047857;">Terima kasih, pembayaran Anda telah kami terima.</p>
    </div>

    <div style="background-color: #f9fafb; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
        <h3 style="margin-top: 0; color: #1f2937;">Halo, {{ $pembayaran->tagihan->penyewaan->penyewa->name }}</h3>
        
        <table style="width: 100%; border-collapse: collapse; margin-top: 15px;">
            <tr>
                <td style="padding: 8px 0; border-bottom: 1px solid #e5e7eb;"><strong>No. Tagihan</strong></td>
                <td style="padding: 8px 0; border-bottom: 1px solid #e5e7eb; text-align: right;">{{ $pembayaran->tagihan->no_tagihan }}</td>
            </tr>
            <tr>
                <td style="padding: 8px 0; border-bottom: 1px solid #e5e7eb;"><strong>Kamar</strong></td>
                <td style="padding: 8px 0; border-bottom: 1px solid #e5e7eb; text-align: right;">{{ $pembayaran->tagihan->penyewaan->kamar->nama }}</td>
            </tr>
            <tr>
                <td style="padding: 8px 0; border-bottom: 1px solid #e5e7eb;"><strong>Metode Pembayaran</strong></td>
                <td style="padding: 8px 0; border-bottom: 1px solid #e5e7eb; text-align: right;">{{ $pembayaran->metode_pembayaran }}</td>
            </tr>
            <tr>
                <td style="padding: 8px 0; border-bottom: 1px solid #e5e7eb;"><strong>Tanggal Bayar</strong></td>
                <td style="padding: 8px 0; border-bottom: 1px solid #e5e7eb; text-align: right;">{{ $pembayaran->paid_at->format('d M Y H:i') }}</td>
            </tr>
            <tr>
                <td style="padding: 12px 0 8px 0; font-size: 1.1em;"><strong>Nominal Diterima</strong></td>
                <td style="padding: 12px 0 8px 0; text-align: right; font-size: 1.1em; color: #059669; font-weight: bold;">Rp {{ number_format($pembayaran->jumlah_diterima, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <div style="text-align: center; border-top: 1px solid #e5e7eb; padding-top: 20px; color: #6b7280; font-size: 0.85em;">
        <p>Email ini adalah bukti pembayaran yang sah dan dikirim secara otomatis.<br>Simpan email ini untuk referensi Anda.</p>
    </div>
</body>
</html>
