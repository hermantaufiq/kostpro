<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tagihan Kos - {{ $tagihan->kode_tagihan }}</title>
</head>
<body style="margin:0;padding:0;background-color:#f1f5f9;font-family:'Segoe UI',Arial,sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f1f5f9;padding:40px 0;">
<tr><td align="center">
<table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:16px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,0.08);">

    {{-- ── HEADER ── --}}
    <tr>
        <td style="background:linear-gradient(135deg,#4f46e5 0%,#7c3aed 100%);padding:36px 40px;text-align:center;">
            <h1 style="margin:0;color:#ffffff;font-size:24px;font-weight:800;letter-spacing:-0.5px;">{{ \App\Models\Setting::get('nama_kos', 'KostPro') }}</h1>
            <p style="margin:6px 0 0;color:#c7d2fe;font-size:13px;">{{ \App\Models\Setting::get('alamat_kos', 'Sistem Manajemen Kos Modern') }}</p>
        </td>
    </tr>

    {{-- ── BADGE DISKON (jika ada) ── --}}
    @if(($tagihan->diskon_persen ?? 0) > 0)
    <tr>
        <td style="background:#fef9c3;border-bottom:2px dashed #fbbf24;padding:14px 40px;text-align:center;">
            <span style="background:#f59e0b;color:#fff;font-size:13px;font-weight:800;padding:4px 14px;border-radius:20px;">🎉 DISKON {{ $tagihan->diskon_persen }}% AKTIF</span>
            <span style="display:inline-block;margin-left:10px;color:#92400e;font-size:13px;">
                Hemat Rp {{ number_format($tagihan->jumlah_diskon, 0, ',', '.') }} untuk paket {{ $tagihan->untuk_durasi_bulan }} bulan!
            </span>
        </td>
    </tr>
    @endif

    {{-- ── GREETING ── --}}
    <tr>
        <td style="padding:32px 40px 0;">
            <p style="margin:0;font-size:16px;color:#374151;">Halo, <strong>{{ $tagihan->penyewaan->penyewa->name ?? $tagihan->user->name }}</strong> 👋</p>
            <p style="margin:8px 0 0;font-size:14px;color:#6b7280;">
                @if(($tagihan->untuk_durasi_bulan ?? 1) > 1)
                    Tagihan perpanjangan sewa <strong>{{ $tagihan->untuk_durasi_bulan }} bulan</strong> telah terbit. Lunasi sebelum jatuh tempo untuk mengamankan kamar Anda!
                @else
                    Tagihan sewa bulanan Anda telah terbit. Lunasi sebelum jatuh tempo agar sewa tetap berjalan lancar.
                @endif
            </p>
        </td>
    </tr>

    {{-- ── RINCIAN TAGIHAN ── --}}
    <tr>
        <td style="padding:24px 40px;">
            <table width="100%" cellpadding="0" cellspacing="0" style="background:#f8fafc;border-radius:12px;border:1px solid #e2e8f0;overflow:hidden;">
                <tr style="background:#f1f5f9;">
                    <td style="padding:12px 20px;font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:0.5px;">Rincian</td>
                    <td style="padding:12px 20px;font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:0.5px;text-align:right;">Nilai</td>
                </tr>
                <tr>
                    <td style="padding:12px 20px;border-top:1px solid #e2e8f0;font-size:14px;color:#374151;">No. Tagihan</td>
                    <td style="padding:12px 20px;border-top:1px solid #e2e8f0;font-size:14px;color:#374151;text-align:right;font-weight:600;font-family:monospace;">{{ $tagihan->kode_tagihan }}</td>
                </tr>
                <tr>
                    <td style="padding:12px 20px;border-top:1px solid #e2e8f0;font-size:14px;color:#374151;">Kamar</td>
                    <td style="padding:12px 20px;border-top:1px solid #e2e8f0;font-size:14px;color:#374151;text-align:right;">{{ $tagihan->penyewaan->kamar->nama ?? '-' }}</td>
                </tr>
                @if(($tagihan->untuk_durasi_bulan ?? 1) > 1)
                <tr>
                    <td style="padding:12px 20px;border-top:1px solid #e2e8f0;font-size:14px;color:#374151;">Periode</td>
                    <td style="padding:12px 20px;border-top:1px solid #e2e8f0;font-size:14px;color:#374151;text-align:right;">{{ $tagihan->untuk_durasi_bulan }} bulan</td>
                </tr>
                @if(($tagihan->diskon_persen ?? 0) > 0)
                <tr>
                    <td style="padding:12px 20px;border-top:1px solid #e2e8f0;font-size:14px;color:#059669;">Diskon {{ $tagihan->diskon_persen }}%</td>
                    <td style="padding:12px 20px;border-top:1px solid #e2e8f0;font-size:14px;color:#059669;text-align:right;">- Rp {{ number_format($tagihan->jumlah_diskon, 0, ',', '.') }}</td>
                </tr>
                @endif
                @endif
                <tr>
                    <td style="padding:12px 20px;border-top:1px solid #e2e8f0;font-size:14px;color:#dc2626;font-weight:600;">⏰ Jatuh Tempo</td>
                    <td style="padding:12px 20px;border-top:1px solid #e2e8f0;font-size:14px;color:#dc2626;text-align:right;font-weight:600;">{{ $tagihan->tanggal_jatuh_tempo->format('d M Y') }}</td>
                </tr>
                <tr style="background:linear-gradient(135deg,#4f46e5,#7c3aed);">
                    <td style="padding:16px 20px;font-size:16px;font-weight:800;color:#ffffff;">Total Bayar</td>
                    <td style="padding:16px 20px;font-size:20px;font-weight:800;color:#ffffff;text-align:right;">Rp {{ number_format($tagihan->jumlah_tagihan, 0, ',', '.') }}</td>
                </tr>
            </table>
        </td>
    </tr>

    {{-- ── TOMBOL BAYAR SEKARANG ── --}}
    <tr>
        <td style="padding:0 40px 32px;text-align:center;">
            <a href="{{ route('user.tagihan.show', $tagihan->id) }}"
               style="display:inline-block;background:linear-gradient(135deg,#4f46e5,#7c3aed);color:#ffffff;font-size:16px;font-weight:800;padding:16px 48px;border-radius:50px;text-decoration:none;box-shadow:0 8px 24px rgba(79,70,229,0.35);">
                💳 Bayar Sekarang
            </a>
            <p style="margin:14px 0 0;font-size:12px;color:#94a3b8;">Klik tombol di atas untuk langsung melakukan pembayaran</p>
        </td>
    </tr>

    {{-- ── CATATAN DENDA ── --}}
    <tr>
        <td style="padding:0 40px 32px;">
            <div style="background:#fff7ed;border-left:4px solid #f97316;border-radius:8px;padding:14px 16px;">
                <p style="margin:0;font-size:13px;color:#9a3412;">
                    ⚠️ <strong>Penting:</strong> Pembayaran yang melewati jatuh tempo akan dikenakan masa tenggang 3 hari. Setelah itu, denda sebesar <strong>{{ \App\Models\Setting::get('denda_persen', 5) }}%</strong> akan otomatis ditambahkan.
                </p>
            </div>
        </td>
    </tr>

    {{-- ── FOOTER ── --}}
    <tr>
        <td style="background:#f8fafc;border-top:1px solid #e2e8f0;padding:24px 40px;text-align:center;">
            <p style="margin:0;font-size:12px;color:#94a3b8;">Email ini dikirim otomatis oleh sistem <strong>{{ \App\Models\Setting::get('nama_kos', 'KostPro') }}</strong>.</p>
            <p style="margin:6px 0 0;font-size:12px;color:#94a3b8;">Butuh bantuan? Hubungi Admin: <strong>{{ \App\Models\Setting::get('kontak_admin', '-') }}</strong></p>
        </td>
    </tr>

</table>
</td></tr>
</table>
</body>
</html>
