<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Tagihan Pembayaran Kos</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="text-align: center; margin-bottom: 30px;">
        <h2>{{ \App\Models\Setting::get('nama_kos', 'KosPro') }}</h2>
        <p style="color: #666;">{{ \App\Models\Setting::get('alamat_kos', '') }}</p>
    </div>

    <div style="background-color: #f9fafb; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
        <h3 style="margin-top: 0; color: #1f2937;">Halo, {{ $tagihan->penyewaan->penyewa->name }}</h3>
        <p>Berikut adalah rincian tagihan kos Anda untuk bulan ini:</p>
        
        <table style="width: 100%; border-collapse: collapse; margin-top: 15px;">
            <tr>
                <td style="padding: 8px 0; border-bottom: 1px solid #e5e7eb;"><strong>No. Tagihan</strong></td>
                <td style="padding: 8px 0; border-bottom: 1px solid #e5e7eb; text-align: right;">{{ $tagihan->no_tagihan }}</td>
            </tr>
            <tr>
                <td style="padding: 8px 0; border-bottom: 1px solid #e5e7eb;"><strong>Kamar</strong></td>
                <td style="padding: 8px 0; border-bottom: 1px solid #e5e7eb; text-align: right;">{{ $tagihan->penyewaan->kamar->nama }}</td>
            </tr>
            <tr>
                <td style="padding: 8px 0; border-bottom: 1px solid #e5e7eb;"><strong>Jatuh Tempo</strong></td>
                <td style="padding: 8px 0; border-bottom: 1px solid #e5e7eb; text-align: right; color: #dc2626;">{{ $tagihan->tanggal_jatuh_tempo->format('d M Y') }}</td>
            </tr>
            <tr>
                <td style="padding: 12px 0 8px 0; font-size: 1.1em;"><strong>Total Tagihan</strong></td>
                <td style="padding: 12px 0 8px 0; text-align: right; font-size: 1.1em; color: #2563eb; font-weight: bold;">Rp {{ number_format($tagihan->total_tagihan, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <div style="margin-bottom: 30px;">
        <h4>Cara Pembayaran:</h4>
        <div style="background-color: #f3f4f6; padding: 15px; border-radius: 6px; white-space: pre-line;">
            {{ \App\Models\Setting::get('rekening_pembayaran', 'Silakan hubungi Admin untuk informasi pembayaran.') }}
        </div>
        <p style="margin-top: 15px; font-size: 0.9em; color: #666;">
            Mohon lakukan pembayaran sebelum tanggal jatuh tempo untuk menghindari denda sebesar Rp {{ number_format(\App\Models\Setting::get('nominal_denda', 0), 0, ',', '.') }} per hari keterlambatan.
        </p>
    </div>

    <div style="text-align: center; border-top: 1px solid #e5e7eb; padding-top: 20px; color: #6b7280; font-size: 0.85em;">
        <p>Email ini dikirim secara otomatis oleh sistem.<br>Jika ada pertanyaan, silakan hubungi Admin via WhatsApp di: {{ \App\Models\Setting::get('kontak_admin', '-') }}</p>
    </div>
</body>
</html>
