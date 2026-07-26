@extends('layouts.app')
@section('title', 'Pengaturan')
@section('page-title', 'Pengaturan')

@section('content')

{{-- Success alert --}}
@if(session('success'))
<div class="bg-green-50 border border-green-200 text-green-700 text-sm rounded-xl px-5 py-3 mb-4">
    {{ session('success') }}
</div>
@endif

{{-- Error alert --}}
@if($errors->any())
<div class="bg-red-50 border border-red-200 text-red-600 text-sm rounded-xl px-5 py-3 mb-4">
    <ul class="list-disc list-inside">
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="max-w-2xl">

    {{-- Tab --}}
    <div class="flex gap-2 mb-5">
        <button onclick="gantiTab('profil')" id="tab-profil"
                class="flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium border border-gray-200 text-gray-600 hover:border-amber-400">
            🏠 Profil Toko
        </button>
        <button onclick="gantiTab('struk')" id="tab-struk"
                class="flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium text-white"
                style="background-color: #8B5E3C;">
            🧾 Struk & Pajak
        </button>
    </div>

    {{-- ===== TAB: PROFIL TOKO ===== --}}
    <div id="content-profil" class="hidden">
        <div class="bg-white rounded-xl shadow-sm p-5">
            <p class="text-sm font-semibold text-gray-700 mb-4">Profil Toko</p>
            <form method="POST" action="{{ route('admin.pengaturan.profil') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <div class="flex items-center gap-4">
                    <img src="{{ $setting->logo_url }}" alt="Logo toko"
                         class="w-16 h-16 rounded-full object-cover border border-gray-200">
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Logo toko</label>
                        <input type="file" name="logo" accept="image/*"
                               class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400">
                        <p class="text-xs text-gray-400 mt-1">Format JPG/PNG/WEBP, maksimal 2MB</p>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Toko <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_toko" value="{{ $setting->nama_toko }}" placeholder="Contoh: Ngopi King!"
                           class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                    <textarea name="alamat" rows="2" placeholder="Alamat lengkap toko"
                              class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400">{{ $setting->alamat }}</textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">No. Telepon/WhatsApp</label>
                        <input type="text" name="no_telepon" value="{{ $setting->no_telepon }}" placeholder="08xxxxxxxxxx"
                               class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" value="{{ $setting->email }}" placeholder="toko@email.com"
                               class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jam Buka</label>
                        <input type="time" name="jam_buka" value="{{ $setting->jam_buka }}"
                               class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jam Tutup</label>
                        <input type="time" name="jam_tutup" value="{{ $setting->jam_tutup }}"
                               class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Singkat</label>
                    <textarea name="deskripsi" rows="2" placeholder="Tampil di halaman self order nanti"
                              class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400">{{ $setting->deskripsi }}</textarea>
                </div>

                <button type="submit"
                        class="text-white text-sm font-medium px-5 py-2 rounded-lg"
                        style="background-color: #8B5E3C;">
                    Simpan Profil Toko
                </button>
            </form>
        </div>
    </div>

    {{-- ===== TAB: STRUK & PAJAK ===== --}}
    <div id="content-struk" class="space-y-5">

        {{-- Pengaturan Pajak --}}
        <div class="bg-white rounded-xl shadow-sm p-5">
            <p class="text-sm font-semibold text-gray-700 mb-4">Pengaturan Pajak</p>
            <form method="POST" action="{{ route('admin.pengaturan.pajak') }}" class="space-y-4">
                @csrf

                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-700">PPN 11%</p>
                        <p class="text-xs text-gray-400">Aktifkan PPN secara default pada transaksi baru</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="ppn_aktif" value="1" class="sr-only peer" {{ old('ppn_aktif', $setting->ppn_aktif) ? 'checked' : '' }}>
                        <div class="w-10 h-5.5 bg-gray-200 peer-checked:bg-amber-600 rounded-full peer transition-colors" style="width:2.5rem;height:1.375rem;"></div>
                        <div class="absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition-transform peer-checked:translate-x-4"></div>
                    </label>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Diskon Maksimal (%)</label>
                    <input type="number" name="diskon_maksimal" value="{{ $setting->diskon_maksimal }}" placeholder="Contoh: 50" min="0" max="100"
                           class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400">
                    <p class="text-xs text-gray-400 mt-1">Batas maksimal diskon yang bisa diberikan kasir</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pembulatan Harga</label>
                    <select name="pembulatan_harga"
                            class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400">
                        <option value="tidak_ada" {{ $setting->pembulatan_harga == 'tidak_ada' ? 'selected' : '' }}>Tidak ada pembulatan</option>
                        <option value="ke_atas_100" {{ $setting->pembulatan_harga == 'ke_atas_100' ? 'selected' : '' }}>Bulatkan ke atas (Rp 100)</option>
                        <option value="ke_bawah_100" {{ $setting->pembulatan_harga == 'ke_bawah_100' ? 'selected' : '' }}>Bulatkan ke bawah (Rp 100)</option>
                        <option value="terdekat_100" {{ $setting->pembulatan_harga == 'terdekat_100' ? 'selected' : '' }}>Bulatkan ke terdekat (Rp 100)</option>
                    </select>
                </div>

                <button type="submit"
                        class="text-white text-sm font-medium px-5 py-2 rounded-lg"
                        style="background-color: #8B5E3C;">
                    Simpan Pengaturan Pajak
                </button>
            </form>
        </div>

        {{-- Nomor Dokumen --}}
        <div class="bg-white rounded-xl shadow-sm p-5">
            <p class="text-sm font-semibold text-gray-700 mb-4">Nomor Dokumen</p>
            <form method="POST" action="{{ route('admin.pengaturan.nomor') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Format Nomor Transaksi</label>
                    <input type="text" name="format_nomor_transaksi" value="{{ $setting->format_nomor_transaksi }}" placeholder="Contoh: DD-MM-YYYY"
                           class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400">
                    <p class="text-xs text-gray-400 mt-1">(DD) = Tanggal, (MM) = Bulan, (YYYY) = Tahun</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Reset Nomor Urut</label>
                    <select name="reset_nomor_urut"
                            class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400">
                        <option value="harian" {{ $setting->reset_nomor_urut == 'harian' ? 'selected' : '' }}>Reset setiap hari</option>
                        <option value="bulanan" {{ $setting->reset_nomor_urut == 'bulanan' ? 'selected' : '' }}>Reset setiap bulan</option>
                        <option value="tahunan" {{ $setting->reset_nomor_urut == 'tahunan' ? 'selected' : '' }}>Reset setiap tahun</option>
                        <option value="tidak_reset" {{ $setting->reset_nomor_urut == 'tidak_reset' ? 'selected' : '' }}>Tidak pernah reset</option>
                    </select>
                </div>

                <button type="submit"
                        class="text-white text-sm font-medium px-5 py-2 rounded-lg"
                        style="background-color: #8B5E3C;">
                    Simpan Format Nomor
                </button>
            </form>
        </div>

        {{-- Printer --}}
        <div class="bg-white rounded-xl shadow-sm p-5">
            <p class="text-sm font-semibold text-gray-700 mb-4">Printer</p>
            <form method="POST" action="{{ route('admin.pengaturan.printer') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Ukuran Kertas</label>
                    <select name="ukuran_kertas"
                            class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400">
                        <option value="thermal_58" {{ $setting->ukuran_kertas == 'thermal_58' ? 'selected' : '' }}>Thermal 58mm</option>
                        <option value="thermal_80" {{ $setting->ukuran_kertas == 'thermal_80' ? 'selected' : '' }}>Thermal 80mm</option>
                        <option value="a4" {{ $setting->ukuran_kertas == 'a4' ? 'selected' : '' }}>A4</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Margin (mm)</label>
                    <input type="number" name="margin" value="{{ $setting->margin }}" placeholder="Contoh: 5" min="0"
                           class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400">
                </div>

                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-700">Cetak Otomatis</p>
                        <p class="text-xs text-gray-400">Cetak struk secara otomatis setelah pembayaran</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="cetak_otomatis" value="1" class="sr-only peer" {{ old('cetak_otomatis', $setting->cetak_otomatis) ? 'checked' : '' }}>
                        <div class="bg-gray-200 peer-checked:bg-amber-600 rounded-full peer transition-colors" style="width:2.5rem;height:1.375rem;"></div>
                        <div class="absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition-transform peer-checked:translate-x-4"></div>
                    </label>
                </div>

                <div class="flex gap-3">
                    <button type="submit"
                            class="text-white text-sm font-medium px-5 py-2 rounded-lg"
                            style="background-color: #8B5E3C;">
                        Simpan Pengaturan Printer
                    </button>
                    <button type="button" onclick="alert('Fitur tes cetak butuh koneksi ke printer fisik, belum tersedia di versi ini.')"
                            class="text-sm font-medium px-5 py-2 rounded-lg border border-gray-200 text-gray-600 hover:border-amber-400">
                        Tes Cetak
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>

<script>
function gantiTab(tab) {
    const tabs = ['profil', 'struk'];
    tabs.forEach(t => {
        const btn = document.getElementById('tab-' + t);
        const content = document.getElementById('content-' + t);
        if (t === tab) {
            btn.className = 'flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium text-white';
            btn.style.backgroundColor = '#8B5E3C';
            content.classList.remove('hidden');
        } else {
            btn.className = 'flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium border border-gray-200 text-gray-600 hover:border-amber-400';
            btn.style.backgroundColor = '';
            content.classList.add('hidden');
        }
    });
    localStorage.setItem('pengaturanTabAktif', tab);
}
document.addEventListener('DOMContentLoaded', function () {
    const tabTersimpan = localStorage.getItem('pengaturanTabAktif') || 'struk';
    gantiTab(tabTersimpan);
});
</script>

@endsection
