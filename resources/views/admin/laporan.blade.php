@extends('layouts.app')
@section('title', 'Laporan')
@section('page-title', 'Laporan')

@section('content')

{{-- Toolbar --}}
<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 mb-6">
    <form method="GET" action="{{ route('admin.laporan') }}" class="flex flex-wrap items-center gap-2">
        <input type="date" name="dari" value="{{ $dari }}"
               class="border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-600 focus:outline-none focus:ring-2 focus:ring-amber-300 focus:border-amber-300">
        <span class="text-gray-400 text-sm">s/d</span>
        <input type="date" name="sampai" value="{{ $sampai }}"
               class="border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-600 focus:outline-none focus:ring-2 focus:ring-amber-300 focus:border-amber-300">
        <button type="submit"
                class="flex items-center gap-1.5 px-4 py-2 rounded-lg text-sm font-medium border border-gray-200 text-gray-600 hover:border-amber-400 hover:text-amber-600 transition-colors">
            <span>📅</span><span>Filter periode</span>
        </button>
    </form>
    <button onclick="window.print()"
            class="flex items-center gap-2 text-sm font-semibold px-5 py-2.5 rounded-lg text-white shadow-sm hover:shadow-md transition-shadow"
            style="background: linear-gradient(135deg, #8B5E3C, #A8734D);">
        🖨️ Cetak Laporan
    </button>
</div>

{{-- Statistik kartu --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

    <div class="rounded-2xl shadow-sm hover:shadow-lg transition-shadow duration-200 p-5 relative overflow-hidden"
         style="background: linear-gradient(135deg, #FAF5FF, #F3E8FF);">
        <div class="absolute -right-4 -top-4 w-20 h-20 rounded-full opacity-20" style="background: linear-gradient(135deg, #A855F7, #7E22CE);"></div>
        <div class="relative flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center text-xl shrink-0 shadow-sm text-white"
                 style="background: linear-gradient(135deg, #C084FC, #9333EA);">🛒</div>
            <div>
                <p class="text-xs text-purple-700/70 font-semibold uppercase tracking-wide">Total transaksi</p>
                <p class="text-2xl font-bold text-purple-700 mt-0.5">{{ $totalTransaksi }}</p>
            </div>
        </div>
    </div>

    <div class="rounded-2xl shadow-sm hover:shadow-lg transition-shadow duration-200 p-5 relative overflow-hidden"
         style="background: linear-gradient(135deg, #EFF6FF, #DBEAFE);">
        <div class="absolute -right-4 -top-4 w-20 h-20 rounded-full opacity-20" style="background: linear-gradient(135deg, #3B82F6, #1D4ED8);"></div>
        <div class="relative flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center text-xl shrink-0 shadow-sm text-white"
                 style="background: linear-gradient(135deg, #60A5FA, #2563EB);">💵</div>
            <div>
                <p class="text-xs text-blue-700/70 font-semibold uppercase tracking-wide">Total penjualan</p>
                <p class="text-2xl font-bold text-blue-700 mt-0.5">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

    <div class="rounded-2xl shadow-sm hover:shadow-lg transition-shadow duration-200 p-5 relative overflow-hidden"
         style="background: linear-gradient(135deg, #F0FDF4, #DCFCE7);">
        <div class="absolute -right-4 -top-4 w-20 h-20 rounded-full opacity-20" style="background: linear-gradient(135deg, #22C55E, #15803D);"></div>
        <div class="relative flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center text-xl shrink-0 shadow-sm text-white"
                 style="background: linear-gradient(135deg, #4ADE80, #16A34A);">⊞</div>
            <div>
                <p class="text-xs text-green-700/70 font-semibold uppercase tracking-wide">QR dan Debit</p>
                <p class="text-2xl font-bold text-green-700 mt-0.5">{{ $qrDebit }}</p>
            </div>
        </div>
    </div>

    <div class="rounded-2xl shadow-sm hover:shadow-lg transition-shadow duration-200 p-5 relative overflow-hidden"
         style="background: linear-gradient(135deg, #FBEEE6, #F5DEC9);">
        <div class="absolute -right-4 -top-4 w-20 h-20 rounded-full opacity-20" style="background: linear-gradient(135deg, #C89666, #8B5E3C);"></div>
        <div class="relative flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center text-xl shrink-0 shadow-sm text-white"
                 style="background: linear-gradient(135deg, #A8734D, #8B5E3C);">💰</div>
            <div>
                <p class="text-xs font-semibold uppercase tracking-wide" style="color: #8B5E3C99;">Tunai</p>
                <p class="text-2xl font-bold mt-0.5" style="color: #8B5E3C;">{{ $tunai }}</p>
            </div>
        </div>
    </div>

</div>

{{-- Grafik Pendapatan Harian --}}
<div class="rounded-2xl shadow-md overflow-hidden mb-6" style="background: white;">
    <div class="px-5 py-4 flex items-center justify-between" style="background: linear-gradient(135deg, #8B5E3C, #A8734D, #C89666);">
        <p class="text-sm font-semibold text-white tracking-wide flex items-center gap-2">
            <span>📈</span><span>Pendapatan Harian</span>
        </p>
        <span class="text-xs text-white/80">{{ $dari }} — {{ $sampai }}</span>
    </div>
    <div class="p-5">
        <canvas id="chartPendapatan" height="80"></canvas>
    </div>
</div>

{{-- Metode Pembayaran & Menu Terlaris --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

    {{-- Donut chart --}}
    <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow duration-200 overflow-hidden border border-gray-100">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-2">
            <span class="w-1.5 h-5 rounded-full" style="background: linear-gradient(180deg, #A855F7, #3B82F6);"></span>
            <p class="text-sm font-semibold text-gray-700">Metode Pembayaran</p>
        </div>
        <div class="p-5 flex items-center gap-6">
            <div class="w-40 h-40 shrink-0">
                <canvas id="chartMetode"></canvas>
            </div>
            <div class="space-y-3 text-sm">
                @foreach($metodePembayaran as $i => $m)
                <div class="flex items-center gap-2.5">
                    <span class="w-3 h-3 rounded-full shrink-0 shadow-sm" style="background-color: {{ ['#22C55E', '#F59E0B', '#3B82F6', '#A855F7'][$i % 4] }};"></span>
                    <span class="text-gray-600">{{ ucfirst($m->metode_pembayaran) }}</span>
                    <span class="font-bold text-gray-800 ml-auto">{{ $m->jumlah }} <span class="text-gray-400 font-normal">({{ round($m->jumlah / max($metodePembayaran->sum('jumlah'), 1) * 100) }}%)</span></span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Menu terlaris --}}
    <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow duration-200 overflow-hidden border border-gray-100">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-2">
            <span class="w-1.5 h-5 rounded-full" style="background: linear-gradient(180deg, #F59E0B, #22C55E);"></span>
            <p class="text-sm font-semibold text-gray-700">Menu Terlaris</p>
        </div>
        <div class="p-5 space-y-4">
            @forelse($menuTerlaris as $i => $item)
            @php
                $barGradients = [
                    'linear-gradient(90deg, #60A5FA, #2563EB)',
                    'linear-gradient(90deg, #C084FC, #9333EA)',
                    'linear-gradient(90deg, #FCD34D, #F59E0B)',
                    'linear-gradient(90deg, #4ADE80, #16A34A)',
                ];
                $percent = round($item->total_qty / $maxQty * 100);
            @endphp
            <div>
                <div class="flex items-center justify-between text-sm mb-1.5">
                    <span class="text-gray-700 flex items-center gap-2">
                        @if($i === 0)
                            <span class="text-xs">🥇</span>
                        @elseif($i === 1)
                            <span class="text-xs">🥈</span>
                        @elseif($i === 2)
                            <span class="text-xs">🥉</span>
                        @endif
                        {{ $item->product->nama_produk ?? '(dihapus)' }}
                    </span>
                    <span class="font-bold text-gray-800">{{ $item->total_qty }}</span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-2.5">
                    <div class="h-2.5 rounded-full transition-all duration-500" style="width: {{ $percent }}%; background: {{ $barGradients[$i % 4] }};"></div>
                </div>
            </div>
            @empty
            <div class="flex flex-col items-center gap-2 py-8 text-gray-400">
                <span class="text-3xl">📭</span>
                <span class="text-sm">Belum ada data penjualan</span>
            </div>
            @endforelse
        </div>
    </div>
</div>

{{-- Chart.js dari CDN --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
<script>
// Data dari server (PHP) diubah jadi JSON, siap dipakai JavaScript
const chartLabels = @json($chartLabels);
const chartData   = @json($chartData);
const metodeLabels = @json($metodePembayaran->pluck('metode_pembayaran')->map(fn($m) => ucfirst($m)));
const metodeData   = @json($metodePembayaran->pluck('jumlah'));

// Gradient fill buat area di bawah garis pendapatan
const ctxPendapatan = document.getElementById('chartPendapatan').getContext('2d');
const gradientFill = ctxPendapatan.createLinearGradient(0, 0, 0, 250);
gradientFill.addColorStop(0, 'rgba(139, 94, 60, 0.25)');
gradientFill.addColorStop(1, 'rgba(139, 94, 60, 0.02)');

// Grafik garis: Pendapatan Harian
new Chart(ctxPendapatan, {
    type: 'line',
    data: {
        labels: chartLabels,
        datasets: [{
            label: 'Pendapatan',
            data: chartData,
            borderColor: '#8B5E3C',
            borderWidth: 2.5,
            backgroundColor: gradientFill,
            tension: 0.4,
            fill: true,
            pointBackgroundColor: '#ffffff',
            pointBorderColor: '#8B5E3C',
            pointBorderWidth: 2,
            pointRadius: 4,
            pointHoverRadius: 6,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: {
                beginAtZero: true,
                grid: { color: '#F3F4F6' },
                ticks: {
                    callback: (value) => 'Rp ' + value.toLocaleString('id-ID')
                }
            },
            x: {
                grid: { display: false }
            }
        }
    }
});

// Donut chart: Metode Pembayaran
new Chart(document.getElementById('chartMetode'), {
    type: 'doughnut',
    data: {
        labels: metodeLabels,
        datasets: [{
            data: metodeData,
            backgroundColor: ['#22C55E', '#F59E0B', '#3B82F6', '#A855F7'],
            borderWidth: 3,
            borderColor: '#ffffff',
            hoverOffset: 6,
        }]
    },
    options: {
        responsive: true,
        cutout: '65%',
        plugins: { legend: { display: false } }
    }
});
</script>

@endsection