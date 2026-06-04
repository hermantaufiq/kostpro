<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $tagihan->kode_tagihan }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            font-size: 14px;
            margin: 0;
            padding: 20px;
        }
        .header {
            width: 100%;
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header table {
            width: 100%;
            border-collapse: collapse;
        }
        .header td {
            vertical-align: top;
        }
        .title {
            color: #4f46e5;
            font-size: 28px;
            font-weight: bold;
            margin: 0;
        }
        .subtitle {
            color: #6b7280;
            font-size: 12px;
            margin: 5px 0 0 0;
        }
        .invoice-title {
            font-size: 20px;
            font-weight: bold;
            color: #111827;
            margin: 0;
            text-align: right;
        }
        .invoice-no {
            color: #6b7280;
            margin: 5px 0;
            text-align: right;
            font-family: monospace;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            float: right;
        }
        .status-paid { background-color: #d1fae5; color: #047857; }
        .status-unpaid { background-color: #ffe4e6; color: #be123c; }
        
        .info-section {
            width: 100%;
            margin-bottom: 30px;
        }
        .info-section table {
            width: 100%;
        }
        .info-section td {
            vertical-align: top;
            width: 50%;
        }
        .info-title {
            font-size: 10px;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 5px;
        }
        .info-text {
            margin: 0 0 4px 0;
            font-size: 13px;
        }
        .info-bold {
            font-weight: bold;
            color: #111827;
        }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .items-table th {
            background-color: #f9fafb;
            color: #4b5563;
            font-size: 11px;
            text-transform: uppercase;
            padding: 10px;
            text-align: left;
            border-top: 1px solid #e5e7eb;
            border-bottom: 1px solid #e5e7eb;
        }
        .items-table th.right { text-align: right; }
        .items-table td {
            padding: 15px 10px;
            border-bottom: 1px solid #f3f4f6;
            vertical-align: top;
        }
        .items-table td.right { text-align: right; font-weight: bold; }
        
        .item-title {
            font-weight: bold;
            color: #111827;
            margin: 0 0 4px 0;
        }
        .item-desc {
            color: #6b7280;
            font-size: 12px;
            margin: 0;
        }
        .item-danger { color: #e11d48; }
        
        .total-row td {
            padding: 15px 10px;
            border-bottom: none;
            border-top: 2px solid #e5e7eb;
        }
        .total-label {
            text-align: right;
            font-weight: bold;
            color: #374151;
        }
        .total-amount {
            text-align: right;
            font-size: 18px;
            font-weight: bold;
            color: #4f46e5;
        }
        
        .footer {
            margin-top: 50px;
            border-top: 1px solid #e5e7eb;
            padding-top: 20px;
            font-size: 11px;
            color: #6b7280;
        }
    </style>
</head>
<body>

    <div class="header">
        <table>
            <tr>
                <td>
                    <h1 class="title">KostPro</h1>
                    <p class="subtitle">Sistem Manajemen Kos Modern</p>
                </td>
                <td>
                    <h2 class="invoice-title">INVOICE</h2>
                    <p class="invoice-no">{{ $tagihan->kode_tagihan }}</p>
                    <div class="status-badge {{ $tagihan->status->value === 'paid' ? 'status-paid' : 'status-unpaid' }}">
                        {{ strtoupper($tagihan->status->value) }}
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="info-section">
        <table>
            <tr>
                <td>
                    <div class="info-title">Ditagihkan Kepada:</div>
                    <p class="info-text info-bold">{{ $tagihan->penyewaan->user->name }}</p>
                    <p class="info-text">{{ $tagihan->penyewaan->user->email }}</p>
                    <p class="info-text">{{ $tagihan->penyewaan->user->phone ?? '-' }}</p>
                </td>
                <td style="text-align: right;">
                    <div class="info-title">Detail Pembayaran:</div>
                    <p class="info-text">Tanggal Tagihan: <span class="info-bold">{{ $tagihan->tanggal_tagihan->format('d M Y') }}</span></p>
                    <p class="info-text">Jatuh Tempo: <span class="info-bold">{{ $tagihan->tanggal_jatuh_tempo->format('d M Y') }}</span></p>
                    <p class="info-text">Metode Bayar: <span class="info-bold">{{ optional(optional($tagihan->pembayaran->first())->metode)->value ?? 'Belum Lunas' }}</span></p>
                </td>
            </tr>
        </table>
    </div>

    <table class="items-table">
        <thead>
            <tr>
                <th>Deskripsi</th>
                <th class="right">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <p class="item-title">Sewa Kamar: {{ $tagihan->penyewaan->kamar->nama }}</p>
                    <p class="item-desc">Periode: {{ date('F', mktime(0, 0, 0, $tagihan->periode_bulan, 10)) }} {{ $tagihan->periode_tahun }}</p>
                </td>
                <td class="right">
                    Rp {{ number_format($tagihan->jumlah_tagihan, 0, ',', '.') }}
                </td>
            </tr>
            @if($tagihan->items && $tagihan->items->count() > 0)
                @foreach($tagihan->items as $item)
                <tr>
                    <td>
                        <p class="item-title">{{ $item->nama_item }}</p>
                    </td>
                    <td class="right">Rp {{ number_format($item->nominal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            @endif
            @if($tagihan->jumlah_denda > 0)
            <tr>
                <td>
                    <p class="item-title item-danger">Denda Keterlambatan</p>
                </td>
                <td class="right item-danger">
                    Rp {{ number_format($tagihan->jumlah_denda, 0, ',', '.') }}
                </td>
            </tr>
            @endif
            <tr class="total-row">
                <td class="total-label">Total Keseluruhan</td>
                <td class="total-amount">
                    Rp {{ number_format($tagihan->jumlah_tagihan + $tagihan->jumlah_denda, 0, ',', '.') }}
                </td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <p><strong>Catatan:</strong> Bukti tagihan ini sah dan diterbitkan secara otomatis oleh sistem KostPro. Harap simpan bukti ini sebagai referensi pembayaran Anda.</p>
    </div>

</body>
</html>
