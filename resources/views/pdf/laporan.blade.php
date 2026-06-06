<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan KostPro</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            line-height: 1.5;
            font-size: 14px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #4f46e5;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            margin: 0;
            color: #4f46e5;
            font-size: 28px;
        }
        .header p {
            margin: 5px 0 0 0;
            color: #666;
        }
        .summary-box {
            display: inline-block;
            width: 23%;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 15px;
            margin-right: 1%;
            box-sizing: border-box;
            text-align: center;
            margin-bottom: 30px;
        }
        .summary-box:last-child {
            margin-right: 0;
        }
        .summary-title {
            font-size: 11px;
            text-transform: uppercase;
            color: #64748b;
            margin-bottom: 5px;
        }
        .summary-value {
            font-size: 20px;
            font-weight: bold;
            color: #0f172a;
        }
        .section-title {
            color: #1e293b;
            border-bottom: 1px solid #cbd5e1;
            padding-bottom: 5px;
            margin-bottom: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        th, td {
            border: 1px solid #cbd5e1;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f1f5f9;
            color: #334155;
            font-size: 13px;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>KOSTPRO</h1>
        <p>Laporan Keuangan & Hunian - Tahun {{ $tahunIni }}</p>
    </div>

    <div>
        <div class="summary-box">
            <div class="summary-title">Occupancy Rate</div>
            <div class="summary-value">{{ $occupancyRate }}%</div>
        </div>
        <div class="summary-box">
            <div class="summary-title">Pendapatan Bulan Ini</div>
            <div class="summary-value">Rp {{ number_format($pendapatanBulanIni, 0, ',', '.') }}</div>
        </div>
        <div class="summary-box">
            <div class="summary-title">Total Tunggakan</div>
            <div class="summary-value">Rp {{ number_format($totalTunggakan, 0, ',', '.') }}</div>
        </div>
        <div class="summary-box">
            <div class="summary-title">Penyewa Aktif</div>
            <div class="summary-value">{{ $totalPenyewaan }}</div>
        </div>
    </div>

    <h2 class="section-title">Pendapatan Bulanan ({{ $tahunIni }})</h2>
    <table>
        <thead>
            <tr>
                <th>Bulan</th>
                <th class="text-right">Pendapatan (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @php
                $bulanNama = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                $totalTahunIni = 0;
            @endphp
            @for ($i = 1; $i <= 12; $i++)
                @php 
                    $val = $pendapatanBulanan[$i] ?? 0;
                    $totalTahunIni += $val;
                @endphp
                <tr>
                    <td>{{ $bulanNama[$i] }}</td>
                    <td class="text-right">{{ number_format($val, 0, ',', '.') }}</td>
                </tr>
            @endfor
            <tr>
                <th>Total Pendapatan Tahun {{ $tahunIni }}</th>
                <th class="text-right">{{ number_format($totalTahunIni, 0, ',', '.') }}</th>
            </tr>
        </tbody>
    </table>

    <h2 class="section-title">Tagihan Belum Dibayar (Tunggakan)</h2>
    @if($tunggakan->isEmpty())
        <p class="text-center">Tidak ada tunggakan saat ini.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>Penghuni</th>
                    <th>Kode Tagihan</th>
                    <th>Jatuh Tempo</th>
                    <th class="text-right">Jumlah (Rp)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tunggakan as $t)
                <tr>
                    <td>{{ $t->user?->name ?? '-' }}</td>
                    <td>{{ $t->kode_tagihan }}</td>
                    <td>{{ \Carbon\Carbon::parse($t->tanggal_jatuh_tempo)->format('d M Y') }}</td>
                    <td class="text-right">{{ number_format($t->total_tagihan, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <h2 class="section-title">Penyewaan Aktif Terbaru</h2>
    @if($penyewaanAktif->isEmpty())
        <p class="text-center">Belum ada penyewaan aktif.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>Penghuni</th>
                    <th>Kamar</th>
                    <th>Tanggal Masuk</th>
                    <th>Durasi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($penyewaanAktif as $p)
                <tr>
                    <td>{{ $p->user?->name ?? '-' }}</td>
                    <td>{{ $p->kamar?->nama ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($p->tanggal_masuk)->format('d M Y') }}</td>
                    <td>{{ $p->durasi_bulan }} Bulan</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div style="margin-top: 50px; text-align: right; font-size: 12px; color: #64748b;">
        Dicetak pada: {{ now()->format('d M Y H:i') }} oleh KostPro System
    </div>
</body>
</html>
