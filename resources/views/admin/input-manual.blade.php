@extends('layouts.app')
@section('title', 'Input Manual')
@section('page-title', 'Input Manual')

@section('content')
<div class="flex gap-5 h-full">

    {{-- KIRI: Grid Produk --}}
    <div class="flex-1 flex flex-col gap-4">

        {{-- Filter & Search --}}
        <div class="flex items-center gap-3">
            <div class="flex gap-2">
                <button onclick="filterKategori('semua')" id="btn-semua"
                        class="px-4 py-1.5 rounded-full text-sm font-medium transition-colors"
                        style="background-color: #8B5E3C; color: white;">
                    Semua produk
                </button>
                @foreach($kategoris as $kat)
                <button onclick="filterKategori('{{ $kat }}')" id="btn-{{ Str::slug($kat) }}"
                        class="px-4 py-1.5 rounded-full text-sm font-medium transition-colors bg-white text-gray-600 border border-gray-200 hover:border-amber-400">
                    {{ $kat }}
                </button>
                @endforeach
            </div>
            <div class="ml-auto">
                <input type="text" id="searchProduk" placeholder="Cari Produk..."
                       onkeyup="searchProduk()"
                       class="border border-gray-200 rounded-lg px-4 py-2 text-sm w-52 focus:outline-none focus:ring-2 focus:ring-amber-400">
            </div>
        </div>

        {{-- Grid --}}
        <div class="overflow-y-auto" style="max-height: calc(100vh - 180px);">
            <div class="grid grid-cols-4 gap-3" id="gridProduk">
                @foreach($products as $product)
                <div class="produk-card bg-white rounded-xl overflow-hidden shadow-sm cursor-pointer hover:shadow-md transition-shadow"
                     data-id="{{ $product->id }}"
                     data-nama="{{ $product->nama_produk }}"
                     data-harga="{{ $product->harga }}"
                     data-kategori="{{ $product->kategori }}"
                     onclick="tambahKeKeranjang({{ $product->id }}, '{{ $product->nama_produk }}', {{ $product->harga }})">
                    <div class="h-28 bg-gray-100 flex items-center justify-center relative overflow-hidden">
                        <img src="{{ $product->gambar_url }}" alt="{{ $product->nama_produk }}"
                             class="w-full h-full object-cover">
                        <span class="absolute top-2 right-2 text-xs px-2 py-0.5 rounded-full text-white"
                              style="background-color: #8B5E3C;">
                            {{ $product->kategori }}
                        </span>
                    </div>
                    <div class="p-3">
                        <p class="text-sm font-medium text-gray-800 truncate">{{ $product->nama_produk }}</p>
                        <p class="text-sm font-bold mt-0.5" style="color: #8B5E3C;">
                            Rp {{ number_format($product->harga, 0, ',', '.') }}
                        </p>
                        <p class="text-xs text-gray-400">Stok: {{ $product->stok }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- KANAN: Keranjang --}}
    <div class="w-80 flex flex-col gap-3">
        <div class="bg-white rounded-xl shadow-sm flex flex-col flex-1 overflow-hidden">

            {{-- Header --}}
            <div class="px-4 py-3 border-b border-gray-100 space-y-2">
                <p class="text-sm font-semibold text-gray-700">Item Dipilih</p>
                <div>
                    <label class="text-xs text-gray-500 mb-1 block">Nomor Meja (opsional)</label>
                    <input type="text" id="inputNomorMeja" placeholder="Contoh: 5, atau kosongkan jika manual"
                           class="w-full border border-gray-200 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-1 focus:ring-amber-400">
                </div>
            </div>

            {{-- Item keranjang --}}
            <div class="flex-1 overflow-y-auto px-4 py-3 space-y-3" id="keranjangList">
                <p class="text-sm text-gray-400 text-center py-4" id="keranjangKosong">Belum ada item dipilih</p>
            </div>

            {{-- Subtotal, diskon, pajak --}}
            <div class="px-4 py-3 border-t border-gray-100 space-y-2 text-sm">
                <div class="flex justify-between text-gray-600">
                    <span>Sub total</span>
                    <span id="subtotalText">Rp 0</span>
                </div>
                <div class="flex justify-between items-center text-gray-600">
                    <span>Diskon (%)</span>
                    <input type="number" id="inputDiskon" value="0" min="0" max="{{ $setting->diskon_maksimal ?? 100 }}"
                           onchange="hitungTotal()"
                           class="w-16 border border-gray-200 rounded px-2 py-1 text-right text-sm focus:outline-none focus:ring-1 focus:ring-amber-400">
                </div>
                <div class="flex justify-between items-center text-gray-600">
                    <span>PPN (%)</span>
                    <div class="flex items-center gap-2">
                        <input type="number" id="inputPajak" value="11" min="0"
                               onchange="hitungTotal()"
                               class="w-16 border border-gray-200 rounded px-2 py-1 text-right text-sm focus:outline-none focus:ring-1 focus:ring-amber-400">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" id="togglePajak" class="sr-only peer" onchange="togglePajakFn()" {{ $setting->ppn_aktif ? 'checked' : '' }}>
                            <div class="w-9 h-5 bg-gray-200 peer-checked:bg-amber-500 rounded-full peer transition-colors"></div>
                            <div class="absolute left-0.5 top-0.5 bg-white w-4 h-4 rounded-full transition-transform peer-checked:translate-x-4"></div>
                        </label>
                    </div>
                </div>
                <div class="flex justify-between font-bold text-gray-800 pt-1 border-t border-gray-100">
                    <span>Total pembayaran</span>
                    <span id="totalText" style="color: #8B5E3C;">Rp 0</span>
                </div>
            </div>

            {{-- Metode bayar --}}
            <div class="px-4 pb-3 space-y-2">
                <p class="text-xs text-gray-500 font-medium">Metode pembayaran</p>
                <div class="flex gap-2">
                    <button onclick="setMetode('tunai')" id="btn-tunai"
                            class="flex-1 py-2 rounded-lg text-xs font-medium border transition-colors border-amber-400 text-amber-600">
                        💵 Tunai
                    </button>
                    <button onclick="setMetode('qris')" id="btn-qris"
                            class="flex-1 py-2 rounded-lg text-xs font-medium border transition-colors border-gray-200 text-gray-500">
                        📱 QRIS
                    </button>
                    <button onclick="setMetode('debit')" id="btn-debit"
                            class="flex-1 py-2 rounded-lg text-xs font-medium border transition-colors border-gray-200 text-gray-500">
                        💳 Debit
                    </button>
                </div>
            </div>

            {{-- Tombol --}}
            <div class="px-4 pb-4 space-y-2">
                <button onclick="openModalBayar()"
                        class="w-full text-white py-2.5 rounded-lg text-sm font-medium"
                        style="background-color: #8B5E3C;">
                    Bayar (<span id="totalBayarBtn">Rp 0</span>)
                </button>
                <button onclick="hapusKeranjang()"
                        class="w-full py-2.5 rounded-lg text-sm font-medium border border-red-300 text-red-500 hover:bg-red-50">
                    🗑️ Hapus keranjang
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Modal Pembayaran --}}
<div id="modalBayar" class="hidden fixed inset-0 flex items-center justify-center z-50" style="background-color: rgba(0,0,0,0.5);">
    <div class="bg-white rounded-2xl p-6 w-full max-w-md shadow-xl">
        <div class="flex items-center justify-between mb-5">
            <h3 class="text-base font-semibold text-gray-800">Pembayaran</h3>
            <button onclick="document.getElementById('modalBayar').classList.add('hidden')"
                    class="text-gray-400 hover:text-gray-600 text-xl">✕</button>
        </div>

        <div class="space-y-4">
            <div class="flex justify-between text-sm">
                <span class="text-gray-500">Total yang harus dibayar</span>
                <span class="font-bold text-lg" style="color: #8B5E3C;" id="modalTotal">Rp 0</span>
            </div>

            <div>
                <p class="text-sm text-gray-500 mb-2">Metode pembayaran</p>
                <div class="flex gap-2">
                    <button onclick="setMetode('tunai')" id="modal-btn-tunai"
                            class="flex-1 py-2 rounded-lg text-xs font-medium border border-amber-400 text-amber-600">
                        💵 Tunai
                    </button>
                    <button onclick="setMetode('qris')" id="modal-btn-qris"
                            class="flex-1 py-2 rounded-lg text-xs font-medium border border-gray-200 text-gray-500">
                        📱 QRIS
                    </button>
                    <button onclick="setMetode('debit')" id="modal-btn-debit"
                            class="flex-1 py-2 rounded-lg text-xs font-medium border border-gray-200 text-gray-500">
                        💳 Debit
                    </button>
                </div>
            </div>

            <div id="sectionJumlahBayar">
                <label class="block text-sm text-gray-500 mb-1">Jumlah bayar</label>
                <input type="number" id="jumlahBayar" placeholder="Masukkan jumlah bayar"
                       onkeyup="hitungKembalian()"
                       class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400">
                <div class="flex gap-2 mt-2 flex-wrap">
                    @foreach([50000, 100000, 150000, 200000] as $nominal)
                    <button onclick="setJumlahBayar({{ $nominal }})"
                            class="px-3 py-1.5 border border-gray-200 rounded-lg text-xs text-gray-600 hover:border-amber-400">
                        Rp {{ number_format($nominal, 0, ',', '.') }}
                    </button>
                    @endforeach
                </div>
            </div>

            <div id="sectionKembalian" class="hidden flex justify-between text-sm font-medium">
                <span class="text-gray-600">Kembalian</span>
                <span id="kembalianText" style="color: #8B5E3C;">Rp 0</span>
            </div>
        </div>

        <div class="flex gap-3 mt-6">
            <button onclick="simpanTransaksi(true)"
                    class="flex-1 text-white py-2.5 rounded-lg text-sm font-medium"
                    style="background-color: #8B5E3C;">
                🖨️ Simpan & Cetak resi
            </button>
            <button onclick="simpanTransaksi(false)"
                    class="flex-1 border py-2.5 rounded-lg text-sm font-medium border-amber-400 text-amber-600">
                Simpan tanpa cetak
            </button>
        </div>
    </div>
</div>

<script>
let keranjang = [];
let metode = 'tunai';
let pajakAktif = {{ $setting->ppn_aktif ? 'true' : 'false' }};
const pembulatanMode = @json($setting->pembulatan_harga ?? 'tidak_ada');
const diskonMaksimal = {{ $setting->diskon_maksimal ?? 100 }};

function terapkanPembulatan(total) {
    if (pembulatanMode === 'ke_atas_100') return Math.ceil(total / 100) * 100;
    if (pembulatanMode === 'ke_bawah_100') return Math.floor(total / 100) * 100;
    if (pembulatanMode === 'terdekat_100') return Math.round(total / 100) * 100;
    return total;
}

function tambahKeKeranjang(id, nama, harga) {
    const idx = keranjang.findIndex(i => i.id === id);
    if (idx >= 0) {
        keranjang[idx].qty++;
    } else {
        keranjang.push({ id, nama, harga, qty: 1 });
    }
    renderKeranjang();
}

function renderKeranjang() {
    const list = document.getElementById('keranjangList');

    if (keranjang.length === 0) {
        list.innerHTML = '<p class="text-sm text-gray-400 text-center py-4" id="keranjangKosong">Belum ada item dipilih</p>';
        hitungTotal();
        return;
    }

    list.innerHTML = keranjang.map((item, idx) => `
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <p class="text-sm font-medium text-gray-800">${item.nama}</p>
                <p class="text-xs text-gray-400">Rp ${formatRp(item.harga)} x${item.qty}</p>
                <p class="text-xs font-medium" style="color: #8B5E3C;">Rp ${formatRp(item.harga * item.qty)}</p>
            </div>
            <div class="flex items-center gap-2 ml-2">
                <button onclick="ubahQty(${idx}, -1)" class="w-6 h-6 rounded-full bg-gray-100 text-gray-600 text-sm hover:bg-gray-200">−</button>
                <span class="text-sm font-medium w-4 text-center">${item.qty}</span>
                <button onclick="ubahQty(${idx}, 1)" class="w-6 h-6 rounded-full text-white text-sm" style="background-color: #8B5E3C;">+</button>
                <button onclick="hapusItem(${idx})" class="text-red-400 hover:text-red-600 ml-1">🗑️</button>
            </div>
        </div>
    `).join('<hr class="border-gray-100">');

    hitungTotal();
}

function ubahQty(idx, delta) {
    keranjang[idx].qty += delta;
    if (keranjang[idx].qty <= 0) keranjang.splice(idx, 1);
    renderKeranjang();
}

function hapusItem(idx) {
    keranjang.splice(idx, 1);
    renderKeranjang();
}

function hapusKeranjang() {
    if (keranjang.length === 0) return;
    if (confirm('Hapus semua item?')) {
        keranjang = [];
        renderKeranjang();
    }
}

function hitungTotal() {
    const subtotal = keranjang.reduce((sum, i) => sum + i.harga * i.qty, 0);
    let diskonPersen = parseFloat(document.getElementById('inputDiskon').value) || 0;

    if (diskonPersen > diskonMaksimal) {
        diskonPersen = diskonMaksimal;
        document.getElementById('inputDiskon').value = diskonMaksimal;
    }

    const pajakPersen = pajakAktif ? (parseFloat(document.getElementById('inputPajak').value) || 0) : 0;
    const diskon = Math.round(subtotal * diskonPersen / 100);
    const pajak  = Math.round((subtotal - diskon) * pajakPersen / 100);
    let total  = subtotal - diskon + pajak;
    total = terapkanPembulatan(total);

    document.getElementById('subtotalText').textContent = 'Rp ' + formatRp(subtotal);
    document.getElementById('totalText').textContent    = 'Rp ' + formatRp(total);
    document.getElementById('totalBayarBtn').textContent = 'Rp ' + formatRp(total);
}

function togglePajakFn() {
    pajakAktif = document.getElementById('togglePajak').checked;
    hitungTotal();
}

function setMetode(m) {
    metode = m;
    ['tunai', 'qris', 'debit'].forEach(x => {
        const btn = document.getElementById('btn-' + x);
        const modalBtn = document.getElementById('modal-btn-' + x);
        if (btn) btn.className = `flex-1 py-2 rounded-lg text-xs font-medium border transition-colors ${x === m ? 'border-amber-400 text-amber-600' : 'border-gray-200 text-gray-500'}`;
        if (modalBtn) modalBtn.className = `flex-1 py-2 rounded-lg text-xs font-medium border ${x === m ? 'border-amber-400 text-amber-600' : 'border-gray-200 text-gray-500'}`;
    });

    document.getElementById('sectionJumlahBayar').style.display = m === 'tunai' ? 'block' : 'none';
}

function openModalBayar() {
    if (keranjang.length === 0) { alert('Keranjang masih kosong!'); return; }
    const subtotal = keranjang.reduce((sum, i) => sum + i.harga * i.qty, 0);
    const diskonPersen = parseFloat(document.getElementById('inputDiskon').value) || 0;
    const pajakPersen  = pajakAktif ? (parseFloat(document.getElementById('inputPajak').value) || 0) : 0;
    const diskon = Math.round(subtotal * diskonPersen / 100);
    const pajak  = Math.round((subtotal - diskon) * pajakPersen / 100);
    let total  = subtotal - diskon + pajak;
    total = terapkanPembulatan(total);

    document.getElementById('modalTotal').textContent = 'Rp ' + formatRp(total);
    document.getElementById('modalBayar').classList.remove('hidden');
    setMetode(metode);
}

function setJumlahBayar(nominal) {
    document.getElementById('jumlahBayar').value = nominal;
    hitungKembalian();
}

function hitungKembalian() {
    const total = parseInt(document.getElementById('modalTotal').textContent.replace(/[^0-9]/g, ''));
    const bayar = parseInt(document.getElementById('jumlahBayar').value) || 0;
    const kembalian = bayar - total;

    const sectionKembalian = document.getElementById('sectionKembalian');
    if (bayar > 0) {
        sectionKembalian.classList.remove('hidden');
        document.getElementById('kembalianText').textContent = 'Rp ' + formatRp(kembalian >= 0 ? kembalian : 0);
    } else {
        sectionKembalian.classList.add('hidden');
    }
}

function simpanTransaksi(cetakResi) {
    if (keranjang.length === 0) { alert('Keranjang kosong!'); return; }

    const diskonPersen = parseFloat(document.getElementById('inputDiskon').value) || 0;
    const pajakPersen  = parseFloat(document.getElementById('inputPajak').value) || 0;
    const jumlahBayar  = parseInt(document.getElementById('jumlahBayar').value) || 0;
    const nomorMeja    = document.getElementById('inputNomorMeja').value.trim();

    const payload = {
        items: keranjang.map(i => ({ product_id: i.id, qty: i.qty })),
        metode_pembayaran: metode,
        diskon_persen: diskonPersen,
        pajak_persen: pajakPersen,
        ppn_aktif: pajakAktif,
        nomor_meja: nomorMeja || null,
        jumlah_bayar: metode === 'tunai' ? jumlahBayar : null,
        _token: '{{ csrf_token() }}',
    };

    fetch('{{ route("admin.input-manual.store") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload),
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            document.getElementById('modalBayar').classList.add('hidden');
            keranjang = [];
            renderKeranjang();
            document.getElementById('inputNomorMeja').value = '';
            if (cetakResi && data.transaction_id) {
                window.open('/admin/struk/' + data.transaction_id, '_blank');
            }
            alert('Transaksi berhasil! Kode: ' + data.kode_transaksi);
        } else {
            alert('Gagal: ' + data.message);
        }
    })
    .catch(() => alert('Terjadi kesalahan!'));
}

function searchProduk() {
    const q = document.getElementById('searchProduk').value.toLowerCase();
    document.querySelectorAll('.produk-card').forEach(card => {
        const nama = card.dataset.nama.toLowerCase();
        card.style.display = nama.includes(q) ? 'block' : 'none';
    });
}

function filterKategori(kat) {
    document.querySelectorAll('.produk-card').forEach(card => {
        card.style.display = (kat === 'semua' || card.dataset.kategori === kat) ? 'block' : 'none';
    });
}

function formatRp(n) {
    return n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}
</script>

@endsection