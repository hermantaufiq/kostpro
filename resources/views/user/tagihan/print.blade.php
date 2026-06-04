<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $tagihan->kode_tagihan }}</title>
    @vite(['resources/css/app.css'])
    <style>
        @media print {
            body { background: white; margin: 0; padding: 0; }
            .no-print { display: none !important; }
            .print-container { max-width: 100%; padding: 20px; box-shadow: none; border: none; }
        }
    </style>
</head>
<body class="bg-slate-100 font-jakarta text-slate-800">
    <div class="max-w-3xl mx-auto my-10 bg-white p-8 rounded-2xl shadow-sm border border-slate-200 print-container">
        
        <div class="flex justify-between items-start border-b border-slate-100 pb-6 mb-6">
            <div>
                <h1 class="text-3xl font-extrabold text-indigo-600">KostPro</h1>
                <p class="text-slate-500 text-sm mt-1">Sistem Manajemen Kos Modern</p>
            </div>
            <div class="text-right">
                <h2 class="text-xl font-bold text-slate-900">INVOICE</h2>
                <p class="text-slate-500 font-mono mt-1">{{ $tagihan->kode_tagihan }}</p>
                <div class="mt-2 inline-block px-3 py-1 rounded-full text-xs font-bold 
                    {{ $tagihan->status->value === 'paid' ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                    {{ strtoupper($tagihan->status->value) }}
                </div>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-8 mb-8">
            <div>
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Ditagihkan Kepada:</h3>
                <p class="font-bold text-slate-900">{{ $tagihan->penyewaan->user->name }}</p>
                <p class="text-sm text-slate-500 mt-1">{{ $tagihan->penyewaan->user->email }}</p>
                <p class="text-sm text-slate-500 mt-1">{{ $tagihan->penyewaan->user->phone ?? '-' }}</p>
            </div>
            <div class="text-right">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Detail Pembayaran:</h3>
                <p class="text-sm"><span class="text-slate-500">Tanggal Tagihan:</span> <span class="font-bold text-slate-900">{{ $tagihan->tanggal_tagihan->format('d M Y') }}</span></p>
                <p class="text-sm mt-1"><span class="text-slate-500">Jatuh Tempo:</span> <span class="font-bold text-slate-900">{{ $tagihan->tanggal_jatuh_tempo->format('d M Y') }}</span></p>
                <p class="text-sm mt-1"><span class="text-slate-500">Metode Bayar:</span> <span class="font-bold text-slate-900">{{ optional(optional($tagihan->pembayaran->first())->metode)->value ?? 'Belum Lunas' }}</span></p>
            </div>
        </div>

        <table class="w-full text-left mb-8">
            <thead>
                <tr class="border-y border-slate-200 bg-slate-50">
                    <th class="py-3 px-4 text-xs font-bold text-slate-600 uppercase">Deskripsi</th>
                    <th class="py-3 px-4 text-xs font-bold text-slate-600 uppercase text-right">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                <tr class="border-b border-slate-100">
                    <td class="py-4 px-4">
                        <p class="font-bold text-slate-900">Sewa Kamar: {{ $tagihan->penyewaan->kamar->nama }}</p>
                        <p class="text-sm text-slate-500 mt-1">Periode: {{ date('F', mktime(0, 0, 0, $tagihan->periode_bulan, 10)) }} {{ $tagihan->periode_tahun }}</p>
                    </td>
                    <td class="py-4 px-4 text-right font-bold text-slate-900">
                        Rp {{ number_format($tagihan->jumlah_tagihan, 0, ',', '.') }}
                    </td>
                </tr>
                @if($tagihan->items && $tagihan->items->count() > 0)
                    @foreach($tagihan->items as $item)
                    <tr class="border-b border-slate-100">
                        <td class="py-4 px-4">{{ $item->nama_item }}</td>
                        <td class="py-4 px-4 text-right font-bold text-slate-900">Rp {{ number_format($item->nominal, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                @endif
                @if($tagihan->jumlah_denda > 0)
                <tr class="border-b border-slate-100">
                    <td class="py-4 px-4">
                        <p class="font-bold text-rose-600">Denda Keterlambatan</p>
                    </td>
                    <td class="py-4 px-4 text-right font-bold text-rose-600">
                        Rp {{ number_format($tagihan->jumlah_denda, 0, ',', '.') }}
                    </td>
                </tr>
                @endif
            </tbody>
            <tfoot>
                <tr>
                    <td class="py-4 px-4 text-right font-bold text-slate-700">Total Keseluruhan</td>
                    <td class="py-4 px-4 text-right text-xl font-extrabold text-indigo-600">
                        Rp {{ number_format($tagihan->jumlah_tagihan + $tagihan->jumlah_denda, 0, ',', '.') }}
                    </td>
                </tr>
            </tfoot>
        </table>

        <div class="border-t border-slate-100 pt-6 text-sm text-slate-500">
            <p><strong>Catatan:</strong> Bukti tagihan ini sah dan diterbitkan secara otomatis oleh sistem KostPro. Harap simpan bukti ini sebagai referensi pembayaran Anda.</p>
        </div>

        <div class="mt-10 flex justify-center gap-4 no-print">
            <button onclick="window.print()" class="btn-primary">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Cetak / Simpan PDF
            </button>
            <a href="{{ route('user.tagihan.show', $tagihan->id) }}" class="btn-secondary">Kembali</a>
        </div>
    </div>
</body>
</html>
