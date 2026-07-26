@extends('layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

{{-- Statistik kartu --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

    <div class="rounded-2xl shadow-sm hover:shadow-lg transition-shadow duration-200 p-5 relative overflow-hidden"
         style="background: linear-gradient(135deg, #FDF2F8, #FCE7F3);">
        <div class="absolute -right-4 -top-4 w-20 h-20 rounded-full opacity-20" style="background: linear-gradient(135deg, #EC4899, #BE185D);"></div>
        <div class="relative flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center text-xl shrink-0 shadow-sm text-white"
                 style="background: linear-gradient(135deg, #F472B6, #DB2777);">🛒</div>
            <div>
                <p class="text-xs text-pink-700/70 font-semibold uppercase tracking-wide">Total transaksi</p>
                <p class="text-2xl font-bold text-pink-600 mt-0.5">{{ $totalTransaksi }}</p>
            </div>
        </div>
    </div>

    <div class="rounded-2xl shadow-sm hover:shadow-lg transition-shadow duration-200 p-5 relative overflow-hidden"
         style="background: linear-gradient(135deg, #EFF6FF, #DBEAFE);">
        <div class="absolute -right-4 -top-4 w-20 h-20 rounded-full opacity-20" style="background: linear-gradient(135deg, #3B82F6, #1D4ED8);"></div>
        <div class="relative flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center text-xl shrink-0 shadow-sm text-white"
                 style="background: linear-gradient(135deg, #60A5FA, #2563EB);">⏳</div>
            <div>
                <p class="text-xs text-blue-700/70 font-semibold uppercase tracking-wide">Pending</p>
                <p class="text-2xl font-bold text-blue-600 mt-0.5">{{ $pending }}</p>
            </div>
        </div>
    </div>

    <div class="rounded-2xl shadow-sm hover:shadow-lg transition-shadow duration-200 p-5 relative overflow-hidden"
         style="background: linear-gradient(135deg, #F0FDF4, #DCFCE7);">
        <div class="absolute -right-4 -top-4 w-20 h-20 rounded-full opacity-20" style="background: linear-gradient(135deg, #22C55E, #15803D);"></div>
        <div class="relative flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center text-xl shrink-0 shadow-sm text-white"
                 style="background: linear-gradient(135deg, #4ADE80, #16A34A);">✅</div>
            <div>
                <p class="text-xs text-green-700/70 font-semibold uppercase tracking-wide">Selesai</p>
                <p class="text-2xl font-bold text-green-600 mt-0.5">{{ $selesai }}</p>
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
                <p class="text-xs font-semibold uppercase tracking-wide" style="color: #8B5E3C99;">Pendapatan</p>
                <p class="text-2xl font-bold mt-0.5" style="color: #8B5E3C;">Rp {{ number_format($pendapatan, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

</div>

{{-- Pesanan Terbaru --}}
<div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">

    <div class="flex items-center justify-between px-5 py-4" style="background: linear-gradient(135deg, #8B5E3C, #A8734D, #C89666);">
        <p class="text-sm font-semibold text-white tracking-wide flex items-center gap-2">
            <span>🧾</span><span>Pesanan Terbaru</span>
        </p>
        <span class="bg-white/20 backdrop-blur-sm text-white text-xs font-semibold px-3 py-1 rounded-full shadow-sm">
            {{ $pending }} pending
        </span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 text-gray-500 border-b border-gray-100">
                <tr>
                    <th class="px-5 py-3 font-semibold uppercase text-xs tracking-wide">No. Transaksi</th>
                    <th class="px-5 py-3 font-semibold uppercase text-xs tracking-wide">Meja</th>
                    <th class="px-5 py-3 font-semibold uppercase text-xs tracking-wide">Item</th>
                    <th class="px-5 py-3 font-semibold uppercase text-xs tracking-wide">Total pembayaran</th>
                    <th class="px-5 py-3 font-semibold uppercase text-xs tracking-wide">Metode bayar</th>
                    <th class="px-5 py-3 font-semibold uppercase text-xs tracking-wide">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($pesananTerbaru as $trx)
                    <tr class="hover:bg-amber-50/40 transition-colors duration-150">
                        <td class="px-5 py-3.5 font-medium text-gray-700">{{ $trx->kode_transaksi }}</td>
                        <td class="px-5 py-3.5 text-gray-600">{{ $trx->nomor_meja ?? 'Manual' }}</td>
                        <td class="px-5 py-3.5 text-gray-600 max-w-xs truncate" title="{{ $trx->details->map(fn($d) => $d->product->nama_produk ?? '(dihapus)')->implode(', ') }}">
                            {{ $trx->details->map(fn($d) => $d->product->nama_produk ?? '(dihapus)')->implode(', ') }}
                        </td>
                        <td class="px-5 py-3.5 font-bold" style="color: #8B5E3C;">Rp {{ number_format($trx->total, 0, ',', '.') }}</td>
                        <td class="px-5 py-3.5 text-gray-600">{{ ucfirst($trx->metode_pembayaran) }}</td>
                        <td class="px-5 py-3.5">
                            @php
                                $badge = match($trx->status) {
                                    'pending' => 'bg-amber-100 text-amber-700',
                                    'selesai' => 'text-white',
                                    'batal'   => 'bg-red-100 text-red-700',
                                    default   => 'bg-gray-100 text-gray-700',
                                };
                                $badgeStyle = $trx->status === 'selesai' ? 'background: linear-gradient(135deg, #A8734D, #8B5E3C);' : '';
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-semibold shadow-sm {{ $badge }}" style="{{ $badgeStyle }}">
                                {{ ucfirst($trx->status) }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-10 text-center text-gray-400">
                            <div class="flex flex-col items-center gap-2">
                                <span class="text-3xl">📭</span>
                                <span>Belum ada transaksi</span>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="flex flex-col sm:flex-row items-center justify-between gap-3 px-5 py-3 text-sm text-gray-500 border-t border-gray-100">
        <span>
            Menampilkan {{ $pesananTerbaru->firstItem() ?? 0 }} - {{ $pesananTerbaru->lastItem() ?? 0 }}
            dari {{ $pesananTerbaru->total() }} data
        </span>
    </div>

    <div class="px-5 pb-4">
        {{ $pesananTerbaru->links() }}
    </div>

</div>

@endsection