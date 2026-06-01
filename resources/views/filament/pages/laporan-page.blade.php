<x-filament-panels::page>
    <div class="space-y-6">

        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                    Laporan Tahun {{ $tahunIni }}
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Ringkasan keuangan dan hunian kos
                </p>
            </div>
        </div>

        {{-- KPI Cards --}}
        <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
            <div class="rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-600 p-5 text-white shadow-lg">
                <p class="text-xs font-semibold uppercase tracking-widest opacity-80">Occupancy Rate</p>
                <p class="mt-2 text-4xl font-extrabold">{{ $occupancyRate }}%</p>
                <p class="mt-1 text-sm opacity-75">{{ $kamarTerisi }} / {{ $totalKamar }} kamar terisi</p>
            </div>
            <div class="rounded-2xl bg-gradient-to-br from-violet-500 to-purple-600 p-5 text-white shadow-lg">
                <p class="text-xs font-semibold uppercase tracking-widest opacity-80">Pendapatan Bulan Ini</p>
                <p class="mt-2 text-3xl font-extrabold">Rp {{ number_format($pendapatanBulanIni, 0, ',', '.') }}</p>
                <p class="mt-1 text-sm opacity-75">Sudah masuk ke rekening</p>
            </div>
            <div class="rounded-2xl bg-gradient-to-br from-rose-500 to-red-600 p-5 text-white shadow-lg">
                <p class="text-xs font-semibold uppercase tracking-widest opacity-80">Total Tunggakan</p>
                <p class="mt-2 text-3xl font-extrabold">Rp {{ number_format($totalTunggakan, 0, ',', '.') }}</p>
                <p class="mt-1 text-sm opacity-75">Tagihan belum terbayar</p>
            </div>
            <div class="rounded-2xl bg-gradient-to-br from-sky-500 to-blue-600 p-5 text-white shadow-lg">
                <p class="text-xs font-semibold uppercase tracking-widest opacity-80">Penyewa Aktif</p>
                <p class="mt-2 text-4xl font-extrabold">{{ $totalPenyewaan }}</p>
                <p class="mt-1 text-sm opacity-75">Kontrak berjalan</p>
            </div>
        </div>

        {{-- Pendapatan Bulanan Chart --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <h3 class="mb-4 text-lg font-bold text-gray-900 dark:text-white">📊 Pendapatan Bulanan {{ $tahunIni }}</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-xs uppercase tracking-wider text-gray-500">
                            <th class="pb-3 pr-4">Bulan</th>
                            <th class="pb-3 pr-4">Pendapatan</th>
                            <th class="pb-3 w-full">Bar</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @php
                            $bulanNama = ['', 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
                            $maxPendapatan = max(array_values($pendapatanBulanan) ?: [1]);
                        @endphp
                        @for ($i = 1; $i <= 12; $i++)
                        @php $val = $pendapatanBulanan[$i] ?? 0; $pct = $maxPendapatan > 0 ? ($val / $maxPendapatan) * 100 : 0; @endphp
                        <tr class="{{ $i === $bulanIni ? 'bg-violet-50 dark:bg-violet-900/20' : '' }}">
                            <td class="py-2 pr-4 font-medium text-gray-700 dark:text-gray-300 w-12">
                                {{ $bulanNama[$i] }}
                                @if($i === $bulanIni) <span class="ml-1 text-xs text-violet-600 font-bold">←</span> @endif
                            </td>
                            <td class="py-2 pr-6 text-gray-600 dark:text-gray-400 whitespace-nowrap w-36">
                                Rp {{ number_format($val, 0, ',', '.') }}
                            </td>
                            <td class="py-2 w-full">
                                <div class="h-5 w-full rounded-full bg-gray-100 dark:bg-gray-700">
                                    <div class="h-5 rounded-full {{ $i === $bulanIni ? 'bg-violet-500' : 'bg-emerald-400' }} transition-all"
                                         style="width: {{ $pct }}%"></div>
                                </div>
                            </td>
                        </tr>
                        @endfor
                    </tbody>
                </table>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">

            {{-- Tunggakan --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <h3 class="mb-4 text-lg font-bold text-gray-900 dark:text-white">⚠️ Tagihan Belum Dibayar</h3>
                @if($tunggakan->isEmpty())
                    <p class="text-center text-gray-400 py-6">Tidak ada tunggakan 🎉</p>
                @else
                <div class="space-y-3">
                    @foreach($tunggakan as $t)
                    <div class="flex items-center justify-between rounded-xl bg-rose-50 p-3 dark:bg-rose-900/20">
                        <div>
                            <p class="font-semibold text-gray-800 dark:text-white text-sm">{{ $t->user?->name ?? '-' }}</p>
                            <p class="text-xs text-gray-500">{{ $t->kode_tagihan }} · Jatuh tempo: {{ \Carbon\Carbon::parse($t->tanggal_jatuh_tempo)->format('d M Y') }}</p>
                        </div>
                        <span class="text-sm font-bold text-rose-600">Rp {{ number_format($t->total_tagihan, 0, ',', '.') }}</span>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            {{-- Penyewaan Aktif --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <h3 class="mb-4 text-lg font-bold text-gray-900 dark:text-white">🏠 Penyewaan Aktif</h3>
                @if($penyewaanAktif->isEmpty())
                    <p class="text-center text-gray-400 py-6">Belum ada penyewa aktif</p>
                @else
                <div class="space-y-3">
                    @foreach($penyewaanAktif as $p)
                    <div class="flex items-center justify-between rounded-xl bg-sky-50 p-3 dark:bg-sky-900/20">
                        <div>
                            <p class="font-semibold text-gray-800 dark:text-white text-sm">{{ $p->user?->name ?? '-' }}</p>
                            <p class="text-xs text-gray-500">{{ $p->kamar?->nama ?? 'Kamar -' }} · Masuk: {{ \Carbon\Carbon::parse($p->tanggal_masuk)->format('d M Y') }}</p>
                        </div>
                        <span class="text-xs font-bold text-sky-600 bg-sky-100 dark:bg-sky-800 px-2 py-1 rounded-full">{{ $p->durasi_bulan }} bln</span>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

        </div>
    </div>
</x-filament-panels::page>
