@extends('layouts.app')
@section('title', 'Pesanan Masuk')
@section('page-title', 'Pesanan Masuk')

@section('content')

@if(session('success'))
<div class="bg-green-50 border border-green-200 text-green-700 text-sm rounded-xl px-5 py-3 mb-4 flex items-center gap-2">
    <span>✅</span><span>{{ session('success') }}</span>
</div>
@endif

<div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
    <div class="flex items-center justify-between px-5 py-4" style="background: linear-gradient(135deg, #8B5E3C, #A8734D, #C89666);">
        <p class="text-sm font-semibold text-white tracking-wide flex items-center gap-2">
            <span>📥</span><span>Pesanan Terbaru</span>
        </p>
        @if($totalPending > 0)
        <span class="bg-white/20 backdrop-blur-sm text-white text-xs font-semibold px-3 py-1 rounded-full shadow-sm">
            {{ $totalPending }} pending
        </span>
        @endif
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
                @forelse($pesanan as $trx)
                <tr class="hover:bg-amber-50/40 transition-colors duration-150">
                    <td class="px-5 py-3.5 font-medium text-gray-700">{{ $trx->kode_transaksi }}</td>
                    <td class="px-5 py-3.5 text-gray-600">{{ $trx->nomor_meja ?? 'Manual' }}</td>
                    <td class="px-5 py-3.5 text-gray-600 max-w-xs truncate"
                        title="{{ $trx->details->map(fn($d) => $d->product->nama_produk . ' x' . $d->qty)->join(', ') }}">
                        {{ $trx->details->map(fn($d) => $d->product->nama_produk . ' x' . $d->qty)->join(', ') }}
                    </td>
                    <td class="px-5 py-3.5 font-bold" style="color: #8B5E3C;">Rp {{ number_format($trx->total, 0, ',', '.') }}</td>
                    <td class="px-5 py-3.5 text-gray-600">{{ strtoupper($trx->metode_pembayaran) }}</td>
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
                    <td class="px-5 py-3.5">
                        <div class="flex items-center gap-1.5 flex-wrap">
                            <button onclick="openDetail({{ $trx->id }})" title="Lihat detail"
                                    class="w-8 h-8 flex items-center justify-center rounded-full text-blue-500 hover:bg-blue-50 transition-colors duration-150">👁️</button>
                            <button onclick="cetakStruk({{ $trx->id }})" title="Cetak struk"
                                    class="w-8 h-8 flex items-center justify-center rounded-full text-gray-500 hover:bg-gray-100 transition-colors duration-150">🖨️</button>

                            <form method="POST" action="{{ route('admin.pesanan.selesai', $trx->id) }}">
                                @csrf
                                <button type="submit"
                                        class="flex items-center gap-1 text-xs font-semibold px-3 py-1.5 rounded-lg text-white shadow-sm hover:shadow transition-shadow"
                                        style="background: linear-gradient(135deg, #A8734D, #8B5E3C);">
                                    ✓ Selesai
                                </button>
                            </form>

                            <form method="POST" action="{{ route('admin.pesanan.batal', $trx->id) }}"
                                  onsubmit="return confirm('Batalkan pesanan ini?')">
                                @csrf
                                <button type="submit"
                                        class="flex items-center gap-1 text-xs font-semibold px-3 py-1.5 rounded-lg border border-red-200 text-red-500 hover:bg-red-50 transition-colors">
                                    ✕ Batal
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-5 py-10 text-center text-gray-400">
                        <div class="flex flex-col items-center gap-2">
                            <span class="text-3xl">📭</span>
                            <span>Tidak ada pesanan pending</span>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="px-5 py-3 text-sm text-gray-500 border-t border-gray-100">
        Menampilkan {{ $pesanan->firstItem() ?? 0 }} - {{ $pesanan->lastItem() ?? 0 }} dari {{ $pesanan->total() }} data
    </div>
</div>

{{-- Modal Detail --}}
<div id="modalDetail" class="hidden fixed inset-0 flex items-center justify-center z-50 px-4" style="background-color: rgba(0,0,0,0.5);">
    <div class="bg-white rounded-2xl p-6 w-full max-w-md shadow-xl">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold text-gray-800" id="detailKode">Detail Pesanan</h3>
            <button onclick="document.getElementById('modalDetail').classList.add('hidden')"
                    class="w-8 h-8 flex items-center justify-center rounded-full text-gray-400 hover:bg-gray-100 hover:text-gray-600 transition-colors text-lg">✕</button>
        </div>
        <div id="detailKonten" class="space-y-2 text-sm text-gray-600"></div>
    </div>
</div>

{{-- Data transaksi untuk JS --}}
<script>
const transaksiData = @json($pesanan->items());

function openDetail(id) {
    const trx = transaksiData.find(t => t.id === id);
    if (!trx) return;

    document.getElementById('detailKode').textContent = trx.kode_transaksi;
    document.getElementById('detailKonten').innerHTML = `
        <div class="rounded-xl p-3 grid grid-cols-2 gap-y-2 gap-x-3 text-sm mb-3" style="background-color: #FBEEE6;">
            <div><span class="text-xs text-gray-500 block">Meja</span><span class="font-medium text-gray-800">${trx.nomor_meja ?? 'Manual'}</span></div>
            <div><span class="text-xs text-gray-500 block">Metode</span><span class="font-medium text-gray-800">${trx.metode_pembayaran.toUpperCase()}</span></div>
        </div>
        <div class="space-y-1.5">
            ${trx.details.map(d => `
                <div class="flex justify-between bg-gray-50 rounded-lg px-3 py-2">
                    <span>${d.product.nama_produk} x${d.qty}</span>
                    <span class="font-medium">Rp ${formatRp(d.subtotal)}</span>
                </div>
            `).join('')}
        </div>
        <div class="flex justify-between font-bold pt-3 mt-3 border-t border-gray-100 text-base">
            <span class="text-gray-800">Total</span>
            <span style="color: #8B5E3C;">Rp ${formatRp(trx.total)}</span>
        </div>
    `;
    document.getElementById('modalDetail').classList.remove('hidden');
}

function cetakStruk(id) {
    window.open('{{ url('/admin/struk') }}/' + id, '_blank');
}

function formatRp(n) {
    return n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}
</script>

@endsection