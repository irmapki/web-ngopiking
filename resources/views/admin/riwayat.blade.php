@extends('layouts.app')
@section('title', 'Riwayat Transaksi')
@section('page-title', 'Riwayat Transaksi')

@section('content')

{{-- Statistik --}}
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">

    <div class="rounded-2xl shadow-sm hover:shadow-lg transition-shadow duration-200 p-5 relative overflow-hidden"
         style="background: linear-gradient(135deg, #FFFBEB, #FEF3C7);">
        <div class="absolute -right-4 -top-4 w-20 h-20 rounded-full opacity-20" style="background: linear-gradient(135deg, #F59E0B, #B45309);"></div>
        <div class="relative flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center text-xl shrink-0 shadow-sm text-white"
                 style="background: linear-gradient(135deg, #FBBF24, #D97706);">🛒</div>
            <div>
                <p class="text-xs text-amber-700/70 font-semibold uppercase tracking-wide">Total transaksi</p>
                <p class="text-2xl font-bold text-amber-700 mt-0.5">{{ $totalTransaksi }}</p>
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
                <p class="text-xs font-semibold uppercase tracking-wide" style="color: #8B5E3C99;">Total penjualan (selesai)</p>
                <p class="text-2xl font-bold mt-0.5" style="color: #8B5E3C;">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

</div>

{{-- Toolbar --}}
<form method="GET" action="{{ route('admin.riwayat') }}" class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-4 gap-3">
    <div class="flex flex-wrap items-center gap-2">
        <input type="date" name="dari" value="{{ request('dari') }}"
               class="border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-600 focus:outline-none focus:ring-2 focus:ring-amber-300 focus:border-amber-300">
        <span class="text-gray-400 text-sm">s/d</span>
        <input type="date" name="sampai" value="{{ request('sampai') }}"
               class="border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-600 focus:outline-none focus:ring-2 focus:ring-amber-300 focus:border-amber-300">
        <button type="submit"
                class="px-4 py-2 rounded-lg text-sm font-medium border border-gray-200 text-gray-600 hover:border-amber-400 hover:text-amber-600 transition-colors">
            Filter periode
        </button>
        @if(request('dari') || request('sampai'))
        <a href="{{ route('admin.riwayat') }}" class="text-xs text-red-400 hover:text-red-600 font-medium">Reset</a>
        @endif
    </div>
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari no. transaksi..."
           class="border border-gray-200 rounded-lg px-4 py-2 text-sm w-full sm:w-64 focus:outline-none focus:ring-2 focus:ring-amber-300 focus:border-amber-300">
</form>

{{-- Tabel --}}
<div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">

    <div class="px-5 py-4" style="background: linear-gradient(135deg, #8B5E3C, #A8734D, #C89666);">
        <p class="text-sm font-semibold text-white tracking-wide flex items-center gap-2">
            <span>🧾</span><span>Daftar Transaksi</span>
        </p>
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
                    <th class="px-5 py-3 font-semibold uppercase text-xs tracking-wide">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($transactions as $trx)
                <tr class="hover:bg-amber-50/40 transition-colors duration-150">
                    <td class="px-5 py-3.5 font-medium text-gray-700">{{ $trx->kode_transaksi }}</td>
                    <td class="px-5 py-3.5 text-gray-600">{{ $trx->nomor_meja ?? 'Manual' }}</td>
                    <td class="px-5 py-3.5 text-gray-600 max-w-xs">
                        @php
                            $itemText = $trx->details->map(fn($d) => ($d->product->nama_produk ?? '(dihapus)') . ' x' . $d->qty)->implode(', ');
                        @endphp
                        <span class="truncate block" title="{{ $itemText }}">{{ \Illuminate\Support\Str::limit($itemText, 40) }}</span>
                    </td>
                    <td class="px-5 py-3.5 font-bold" style="color: #8B5E3C;">Rp {{ number_format($trx->total, 0, ',', '.') }}</td>
                    <td class="px-5 py-3.5 text-gray-600">{{ strtoupper($trx->metode_pembayaran) }}</td>
                    <td class="px-5 py-3.5">
                        @php
                            $statusStyle = match($trx->status) {
                                'selesai' => 'bg-green-100 text-green-700',
                                'pending' => 'bg-amber-100 text-amber-700',
                                'batal'   => 'bg-red-100 text-red-600',
                                default   => 'bg-gray-100 text-gray-500',
                            };
                        @endphp
                        <span class="px-3 py-1 rounded-full text-xs font-semibold shadow-sm {{ $statusStyle }}">
                            {{ ucfirst($trx->status) }}
                        </span>
                    </td>
                    <td class="px-5 py-3.5">
                        <div class="flex items-center gap-2">
                            <button onclick="lihatDetail({{ $trx->id }})" title="Lihat detail"
                                    class="w-8 h-8 flex items-center justify-center rounded-full text-blue-500 hover:bg-blue-50 transition-colors duration-150">👁️</button>
                            <button onclick="cetakResi({{ $trx->id }})" title="Cetak resi"
                                    class="w-8 h-8 flex items-center justify-center rounded-full text-gray-500 hover:bg-gray-100 transition-colors duration-150">🖨️</button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-5 py-10 text-center text-gray-400">
                        <div class="flex flex-col items-center gap-2">
                            <span class="text-3xl">📭</span>
                            <span>Belum ada riwayat transaksi</span>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="flex flex-col sm:flex-row items-center justify-between gap-3 px-5 py-3 border-t border-gray-100">
        <p class="text-sm text-gray-500">
            Menampilkan {{ $transactions->firstItem() ?? 0 }} - {{ $transactions->lastItem() ?? 0 }} dari {{ $transactions->total() }} data
        </p>
        {{ $transactions->links() }}
    </div>
</div>

{{-- Modal Detail --}}
<div id="modalDetail" class="hidden fixed inset-0 flex items-center justify-center z-50 px-4" style="background-color: rgba(0,0,0,0.5);">
    <div class="bg-white rounded-2xl p-6 w-full max-w-md shadow-xl max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between mb-5">
            <h3 class="text-base font-semibold text-gray-800">Detail Transaksi</h3>
            <button onclick="document.getElementById('modalDetail').classList.add('hidden')"
                    class="w-8 h-8 flex items-center justify-center rounded-full text-gray-400 hover:bg-gray-100 hover:text-gray-600 transition-colors text-lg">✕</button>
        </div>

        <div id="detailLoading" class="text-center text-sm text-gray-400 py-6">Memuat...</div>

        <div id="detailContent" class="hidden space-y-4">

            {{-- Info box peach --}}
            <div class="rounded-xl p-4 grid grid-cols-2 gap-y-3 gap-x-4 text-sm border border-amber-100" style="background-color: #FBEEE6;">
                <div>
                    <p class="text-xs text-gray-500">No. Transaksi</p>
                    <p class="font-semibold text-gray-800" id="d_kode"></p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Tanggal/Jam</p>
                    <p class="font-semibold text-gray-800"><span id="d_tanggal"></span> <span id="d_jam"></span></p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Kasir</p>
                    <p class="font-semibold text-gray-800" id="d_kasir"></p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Status</p>
                    <span id="d_status" class="inline-block px-3 py-1 rounded-full text-xs font-semibold mt-0.5 shadow-sm"></span>
                </div>
            </div>

            {{-- Item transaksi --}}
            <div>
                <p class="text-sm font-semibold text-gray-700 mb-2">Item Transaksi</p>
                <div id="d_items" class="space-y-2"></div>
            </div>

            {{-- Ringkasan total --}}
            <div class="border-t border-gray-100 pt-3 space-y-1.5 text-sm">
                <div class="flex justify-between text-gray-600"><span>Sub total</span><span id="d_subtotal"></span></div>
                <div id="d_diskon_row" class="flex justify-between text-gray-600"><span>Diskon</span><span id="d_diskon"></span></div>
                <div class="flex justify-between text-gray-600"><span id="d_pajak_label">PPN</span><span id="d_pajak"></span></div>
                <div class="flex justify-between font-bold pt-2 border-t border-gray-100 text-base">
                    <span class="text-gray-800">Total pembayaran</span>
                    <span id="d_total" style="color: #8B5E3C;"></span>
                </div>
            </div>

            {{-- Pembayaran --}}
            <div class="border-t border-gray-100 pt-3 space-y-1.5 text-sm">
                <p class="font-semibold text-gray-700">Pembayaran</p>
                <div class="flex justify-between text-gray-600">
                    <span id="d_metode"></span>
                    <span id="d_bayar" class="font-medium text-gray-800"></span>
                </div>
                <div id="d_kembalian_row" class="flex justify-between">
                    <span class="text-green-600">Kembalian</span>
                    <span id="d_kembalian" class="font-medium text-green-600"></span>
                </div>
            </div>

            {{-- Tombol --}}
            <div class="flex gap-3 pt-2">
                <button onclick="document.getElementById('modalDetail').classList.add('hidden')"
                        class="flex-1 border border-gray-200 text-gray-600 py-2.5 rounded-lg text-sm font-medium hover:bg-gray-50 transition-colors">
                    Tutup
                </button>
                <button onclick="cetakResi(currentDetailId)"
                        class="flex-1 text-white py-2.5 rounded-lg text-sm font-semibold shadow-sm hover:shadow transition-shadow"
                        style="background: linear-gradient(135deg, #A8734D, #8B5E3C);">
                    🖨️ Cetak ulang
                </button>
            </div>
        </div>
    </div>
</div>

<script>
const strukBaseUrl = "{{ url('/admin/struk') }}";

function formatRp(n) {
    return 'Rp ' + (n ?? 0).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

let currentDetailId = null;

function lihatDetail(id) {
    currentDetailId = id;
    document.getElementById('modalDetail').classList.remove('hidden');
    document.getElementById('detailLoading').classList.remove('hidden');
    document.getElementById('detailContent').classList.add('hidden');

    fetch('/admin/riwayat/' + id)
        .then(r => r.json())
        .then(data => {
            document.getElementById('d_kode').textContent = data.kode_transaksi;
            document.getElementById('d_tanggal').textContent = data.tanggal;
            document.getElementById('d_jam').textContent = data.jam;
            document.getElementById('d_kasir').textContent = data.kasir;

            const statusStyle = {
                selesai: 'bg-green-100 text-green-700',
                pending: 'bg-amber-100 text-amber-700',
                batal:   'bg-red-100 text-red-600',
            };
            const statusEl = document.getElementById('d_status');
            statusEl.textContent = data.status.charAt(0).toUpperCase() + data.status.slice(1);
            statusEl.className = 'inline-block px-3 py-1 rounded-full text-xs font-semibold mt-0.5 shadow-sm ' + (statusStyle[data.status] || 'bg-gray-100 text-gray-500');

            document.getElementById('d_subtotal').textContent = formatRp(data.subtotal);
            document.getElementById('d_total').textContent = formatRp(data.total);
            document.getElementById('d_pajak').textContent = formatRp(data.pajak);
            document.getElementById('d_pajak_label').textContent = 'PPN';

            const diskonRow = document.getElementById('d_diskon_row');
            if (data.diskon > 0) {
                diskonRow.classList.remove('hidden');
                document.getElementById('d_diskon').textContent = '- ' + formatRp(data.diskon);
            } else {
                diskonRow.classList.add('hidden');
            }

            document.getElementById('d_metode').textContent = data.metode_pembayaran;
            document.getElementById('d_bayar').textContent = data.jumlah_bayar ? formatRp(data.jumlah_bayar) : formatRp(data.total);

            const kembalianRow = document.getElementById('d_kembalian_row');
            if (data.kembalian > 0) {
                kembalianRow.classList.remove('hidden');
                document.getElementById('d_kembalian').textContent = formatRp(data.kembalian);
            } else {
                kembalianRow.classList.add('hidden');
            }

            document.getElementById('d_items').innerHTML = data.items.map(item => `
                <div class="flex items-start justify-between bg-gray-50 rounded-lg px-3 py-2">
                    <div>
                        <p class="text-sm font-medium text-gray-800">${item.nama}</p>
                        <p class="text-xs text-gray-400">${formatRp(item.harga)} x${item.qty}</p>
                    </div>
                    <span class="text-sm font-semibold" style="color: #8B5E3C;">${formatRp(item.subtotal)}</span>
                </div>
            `).join('');

            document.getElementById('detailLoading').classList.add('hidden');
            document.getElementById('detailContent').classList.remove('hidden');
        })
        .catch(() => {
            document.getElementById('detailLoading').textContent = 'Gagal memuat detail.';
        });
}

function cetakResi(id) {
    window.open(strukBaseUrl + '/' + id, '_blank');
}
</script>

@endsection