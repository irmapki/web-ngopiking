@extends('layouts.app')
@section('title', 'Barang & Stok')
@section('page-title', 'Barang & Stok')

@section('content')

{{-- Alert stok minim --}}
@if($stokMinim > 0)
<div class="bg-amber-50 border border-amber-200 rounded-xl px-5 py-3 mb-4 flex items-center gap-3">
    <span class="text-lg">📦</span>
    <span class="text-amber-700 text-sm font-medium">{{ $stokMinim }} barang memiliki stok di bawah minimum</span>
</div>
@endif

{{-- Success alert --}}
@if(session('success'))
<div class="bg-green-50 border border-green-200 text-green-700 text-sm rounded-xl px-5 py-3 mb-4 flex items-center gap-2">
    <span>✅</span><span>{{ session('success') }}</span>
</div>
@endif

{{-- Toolbar --}}
<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 mb-6">
    <form method="GET" action="{{ route('admin.produk') }}">
        <select name="kategori" onchange="this.form.submit()"
                class="border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-600 focus:outline-none focus:ring-2 focus:ring-amber-300 focus:border-amber-300">
            <option value="semua">Semua kategori</option>
            @foreach($kategoris as $kat)
                <option value="{{ $kat }}" {{ request('kategori') == $kat ? 'selected' : '' }}>{{ $kat }}</option>
            @endforeach
        </select>
    </form>

    <div class="flex flex-wrap items-center gap-3 w-full sm:w-auto">
        <form method="GET" action="{{ route('admin.produk') }}" class="flex-1 sm:flex-none">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari nama atau kode barang..."
                   class="border border-gray-200 rounded-lg px-4 py-2 text-sm w-full sm:w-64 focus:outline-none focus:ring-2 focus:ring-amber-300 focus:border-amber-300">
        </form>
        <button onclick="document.getElementById('modalTambah').classList.remove('hidden')"
                class="flex items-center gap-2 text-white text-sm font-semibold px-4 py-2 rounded-lg shadow-sm hover:shadow transition-shadow"
                style="background: linear-gradient(135deg, #A8734D, #8B5E3C);">
            <span>+</span><span>Tambah Barang</span>
        </button>
    </div>
</div>

{{-- Tabel --}}
<div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">

    <div class="px-5 py-4" style="background: linear-gradient(135deg, #8B5E3C, #A8734D, #C89666);">
        <p class="text-sm font-semibold text-white tracking-wide flex items-center gap-2">
            <span>📦</span><span>Daftar Barang</span>
        </p>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 text-gray-500 border-b border-gray-100">
                <tr>
                    <th class="px-5 py-3 font-semibold uppercase text-xs tracking-wide">Gambar</th>
                    <th class="px-5 py-3 font-semibold uppercase text-xs tracking-wide">Kode barang</th>
                    <th class="px-5 py-3 font-semibold uppercase text-xs tracking-wide">Nama barang</th>
                    <th class="px-5 py-3 font-semibold uppercase text-xs tracking-wide">Kategori</th>
                    <th class="px-5 py-3 font-semibold uppercase text-xs tracking-wide">Harga jual</th>
                    <th class="px-5 py-3 font-semibold uppercase text-xs tracking-wide">Stok</th>
                    <th class="px-5 py-3 font-semibold uppercase text-xs tracking-wide">Min. stok</th>
                    <th class="px-5 py-3 font-semibold uppercase text-xs tracking-wide">Status</th>
                    <th class="px-5 py-3 font-semibold uppercase text-xs tracking-wide">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($products as $product)
                <tr class="hover:bg-amber-50/40 transition-colors duration-150">
                    <td class="px-5 py-3">
                        <img src="{{ $product->gambar_url }}" alt="{{ $product->nama_produk }}"
                             class="w-12 h-12 object-cover rounded-lg border border-gray-200 shadow-sm">
                    </td>
                    <td class="px-5 py-3.5 text-gray-600">{{ $product->kode_barang }}</td>
                    <td class="px-5 py-3.5 font-medium text-gray-800">{{ $product->nama_produk }}</td>
                    <td class="px-5 py-3.5">
                        <span class="text-xs font-medium px-2.5 py-1 rounded-full bg-gray-100 text-gray-600">{{ $product->kategori }}</span>
                    </td>
                    <td class="px-5 py-3.5 font-semibold" style="color: #8B5E3C;">Rp {{ number_format($product->harga, 0, ',', '.') }}</td>
                    <td class="px-5 py-3.5">
                        @if($product->stok < $product->minimum_stok)
                            <span class="inline-flex items-center gap-1 font-bold text-red-600">⚠ {{ $product->stok }}</span>
                        @else
                            <span class="text-gray-600">{{ $product->stok }}</span>
                        @endif
                    </td>
                    <td class="px-5 py-3.5 text-gray-500">{{ $product->minimum_stok }}</td>
                    <td class="px-5 py-3.5">
                        <span class="px-3 py-1 rounded-full text-xs font-semibold shadow-sm {{ $product->status ? 'text-white' : 'bg-gray-100 text-gray-500' }}"
                              style="{{ $product->status ? 'background: linear-gradient(135deg, #60A5FA, #2563EB);' : '' }}">
                            {{ $product->status ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td class="px-5 py-3.5">
                        <div class="flex items-center gap-1.5">
                            <button onclick="openEdit({{ $product }})" title="Edit"
                                    class="w-8 h-8 flex items-center justify-center rounded-full text-blue-500 hover:bg-blue-50 transition-colors duration-150">✏️</button>
                            <form method="POST" action="{{ route('admin.produk.destroy', $product) }}"
                                  onsubmit="return confirm('Hapus barang ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" title="Hapus"
                                        class="w-8 h-8 flex items-center justify-center rounded-full text-red-400 hover:bg-red-50 hover:text-red-600 transition-colors duration-150">🗑️</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="px-5 py-10 text-center text-gray-400">
                        <div class="flex flex-col items-center gap-2">
                            <span class="text-3xl">📭</span>
                            <span>Belum ada barang</span>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-5 py-3 text-sm text-gray-500 border-t border-gray-100">
        Menampilkan {{ $products->firstItem() ?? 0 }} - {{ $products->lastItem() ?? 0 }} dari {{ $products->total() }} data
    </div>
</div>

{{-- Modal Tambah --}}
<div id="modalTambah" class="hidden fixed inset-0 flex items-center justify-center z-50 px-4" style="background-color: rgba(0,0,0,0.5);">
    <div class="bg-white rounded-2xl p-6 w-full max-w-lg shadow-xl max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between mb-5">
            <h3 class="text-base font-semibold text-gray-800">Tambah Barang Baru</h3>
            <button onclick="document.getElementById('modalTambah').classList.add('hidden')"
                    class="w-8 h-8 flex items-center justify-center rounded-full text-gray-400 hover:bg-gray-100 hover:text-gray-600 transition-colors text-lg">✕</button>
        </div>
        <form method="POST" action="{{ route('admin.produk.store') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Gambar barang</label>
                <input type="file" name="gambar" accept="image/*"
                       class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-300 focus:border-amber-300">
                <p class="text-xs text-gray-400 mt-1">Format JPG/PNG/WEBP, maksimal 2MB</p>
                @error('gambar')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama barang <span class="text-red-500">*</span></label>
                <input type="text" name="nama_produk" placeholder="Contoh: Barang A"
                       class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-300 focus:border-amber-300">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kode barang <span class="text-red-500">*</span></label>
                    <input type="text" name="kode_barang" placeholder="Contoh: BRG-001"
                           class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-300 focus:border-amber-300">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kategori <span class="text-red-500">*</span></label>
                    <input type="text" name="kategori" placeholder="Contoh: Minuman"
                           class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-300 focus:border-amber-300">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi (opsional)</label>
                <textarea name="deskripsi" placeholder="Deskripsi singkat barang (opsional)" rows="2"
                          class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-300 focus:border-amber-300"></textarea>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Harga jual (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" name="harga" placeholder="0"
                           class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-300 focus:border-amber-300">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Stok awal <span class="text-red-500">*</span></label>
                    <input type="number" name="stok" placeholder="0"
                           class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-300 focus:border-amber-300">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Minimum stok <span class="text-red-500">*</span></label>
                <input type="number" name="minimum_stok" placeholder="0"
                       class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-300 focus:border-amber-300">
            </div>
            <div class="flex items-center gap-3 bg-gray-50 rounded-lg px-4 py-3">
                <input type="checkbox" name="status" checked id="tambah_status" class="w-4 h-4 accent-amber-600">
                <label for="tambah_status" class="text-sm font-medium text-gray-700 cursor-pointer">Status aktif</label>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button"
                        onclick="document.getElementById('modalTambah').classList.add('hidden')"
                        class="flex-1 border border-gray-200 text-gray-600 py-2.5 rounded-lg text-sm hover:bg-gray-50 transition-colors">
                    Batal
                </button>
                <button type="submit"
                        class="flex-1 text-white py-2.5 rounded-lg text-sm font-semibold shadow-sm hover:shadow transition-shadow"
                        style="background: linear-gradient(135deg, #A8734D, #8B5E3C);">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Edit --}}
<div id="modalEdit" class="hidden fixed inset-0 flex items-center justify-center z-50 px-4" style="background-color: rgba(0,0,0,0.5);">
    <div class="bg-white rounded-2xl p-6 w-full max-w-lg shadow-xl max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between mb-5">
            <h3 class="text-base font-semibold text-gray-800">Edit Barang</h3>
            <button onclick="document.getElementById('modalEdit').classList.add('hidden')"
                    class="w-8 h-8 flex items-center justify-center rounded-full text-gray-400 hover:bg-gray-100 hover:text-gray-600 transition-colors text-lg">✕</button>
        </div>
        <form method="POST" id="formEdit" enctype="multipart/form-data" class="space-y-4">
            @csrf @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Gambar saat ini</label>
                <img id="edit_gambar_preview" src="" alt="preview"
                     class="w-20 h-20 object-cover rounded-lg mb-2 border border-gray-200 shadow-sm">
                <input type="file" name="gambar" accept="image/*"
                       class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-300 focus:border-amber-300">
                <p class="text-xs text-gray-400 mt-1">Kosongkan jika tidak ingin mengganti gambar</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama barang</label>
                <input type="text" name="nama_produk" id="edit_nama"
                       class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-300 focus:border-amber-300">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kode barang</label>
                    <input type="text" name="kode_barang" id="edit_kode"
                           class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-300 focus:border-amber-300">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                    <input type="text" name="kategori" id="edit_kategori"
                           class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-300 focus:border-amber-300">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                <textarea name="deskripsi" id="edit_deskripsi" rows="2"
                          class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-300 focus:border-amber-300"></textarea>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Harga jual (Rp)</label>
                    <input type="number" name="harga" id="edit_harga"
                           class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-300 focus:border-amber-300">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Stok</label>
                    <input type="number" name="stok" id="edit_stok"
                           class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-300 focus:border-amber-300">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Minimum stok</label>
                <input type="number" name="minimum_stok" id="edit_minimum_stok"
                       class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-300 focus:border-amber-300">
            </div>
            <div class="flex items-center gap-3 bg-gray-50 rounded-lg px-4 py-3">
                <input type="checkbox" name="status" id="edit_status" class="w-4 h-4 accent-amber-600">
                <label for="edit_status" class="text-sm font-medium text-gray-700 cursor-pointer">Status aktif</label>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button"
                        onclick="document.getElementById('modalEdit').classList.add('hidden')"
                        class="flex-1 border border-gray-200 text-gray-600 py-2.5 rounded-lg text-sm hover:bg-gray-50 transition-colors">
                    Batal
                </button>
                <button type="submit"
                        class="flex-1 text-white py-2.5 rounded-lg text-sm font-semibold shadow-sm hover:shadow transition-shadow"
                        style="background: linear-gradient(135deg, #A8734D, #8B5E3C);">
                    Update
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openEdit(product) {
    document.getElementById('edit_nama').value = product.nama_produk;
    document.getElementById('edit_kode').value = product.kode_barang;
    document.getElementById('edit_kategori').value = product.kategori;
    document.getElementById('edit_deskripsi').value = product.deskripsi ?? '';
    document.getElementById('edit_harga').value = product.harga;
    document.getElementById('edit_stok').value = product.stok;
    document.getElementById('edit_minimum_stok').value = product.minimum_stok;
    document.getElementById('edit_status').checked = product.status == 1;
    document.getElementById('edit_gambar_preview').src = product.gambar
        ? '/storage/' + product.gambar
        : '/images/no-image.png';
    document.getElementById('formEdit').action = '/admin/produk/' + product.id;
    document.getElementById('modalEdit').classList.remove('hidden');
}
</script>

@endsection