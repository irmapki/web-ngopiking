<aside class="w-64 flex flex-col flex-shrink-0 h-screen" style="background-color: #8B5E3C;">

    {{-- Logo --}}
    <div class="px-6 py-5">
        <h1 class="text-lg font-bold text-white">Ngopi King!</h1>
        <p class="text-xs text-orange-200">Kasir</p>
    </div>

    {{-- Menu --}}
    <nav class="flex-1 px-3 py-2 space-y-1 overflow-y-auto">

        <a href="{{ route('admin.dashboard') }}"
           class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm transition-colors
           {{ request()->routeIs('admin.dashboard') ? 'text-white font-medium' : 'text-orange-100 hover:text-white' }}"
           style="{{ request()->routeIs('admin.dashboard') ? 'background-color: #7A5230;' : '' }}">
            <span>⊞</span><span>Dashboard</span>
        </a>

        <a href="{{ route('admin.pesanan') }}"
            class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm transition-colors
            {{ request()->routeIs('admin.pesanan') ? 'text-white font-medium' : 'text-orange-100 hover:text-white' }}"
            style="{{ request()->routeIs('admin.pesanan') ? 'background-color: #7A5230;' : '' }}">
                <span>☰</span><span class="flex-1">Pesanan Masuk</span>
                @if($pendingCount > 0)
                <span class="bg-orange-400 text-white text-xs rounded-full px-2 py-0.5">{{ $pendingCount }}</span>
                @endif
        </a>

        <a href="{{ route('admin.input-manual') }}"
           class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm transition-colors
           {{ request()->routeIs('admin.input-manual') ? 'text-white font-medium' : 'text-orange-100 hover:text-white' }}"
           style="{{ request()->routeIs('admin.input-manual') ? 'background-color: #7A5230;' : '' }}">
            <span>⊙</span><span>Input Manual</span>
        </a>

        <a href="{{ route('admin.riwayat') }}"
           class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm transition-colors
           {{ request()->routeIs('admin.riwayat') ? 'text-white font-medium' : 'text-orange-100 hover:text-white' }}"
           style="{{ request()->routeIs('admin.riwayat') ? 'background-color: #7A5230;' : '' }}">
            <span>↺</span><span>Riwayat Transaksi</span>
        </a>

        @if(Auth::user()->role === 'admin')
        <a href="{{ route('admin.produk') }}"
           class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm transition-colors
           {{ request()->routeIs('admin.produk') ? 'text-white font-medium' : 'text-orange-100 hover:text-white' }}"
           style="{{ request()->routeIs('admin.produk') ? 'background-color: #7A5230;' : '' }}">
            <span>▧</span><span>Barang & Stok</span>
        </a>

        <a href="{{ route('admin.laporan') }}"
           class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm transition-colors
           {{ request()->routeIs('admin.laporan') ? 'text-white font-medium' : 'text-orange-100 hover:text-white' }}"
           style="{{ request()->routeIs('admin.laporan') ? 'background-color: #7A5230;' : '' }}">
            <span>▤</span><span>Laporan</span>
        </a>

        <a href="{{ route('admin.pengaturan') }}"
           class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm transition-colors
           {{ request()->routeIs('admin.pengaturan') ? 'text-white font-medium' : 'text-orange-100 hover:text-white' }}"
           style="{{ request()->routeIs('admin.pengaturan') ? 'background-color: #7A5230;' : '' }}">
            <span>⚙</span><span>Pengaturan</span>
        </a>

        <a href="{{ route('admin.akun') }}"
           class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm transition-colors
           {{ request()->routeIs('admin.akun') ? 'text-white font-medium' : 'text-orange-100 hover:text-white' }}"
           style="{{ request()->routeIs('admin.akun') ? 'background-color: #7A5230;' : '' }}">
            <span>◉</span><span>Kelola Akun</span>
        </a>
        @endif

    </nav>
{{-- Logout --}}
<div class="px-3 py-4" style="border-top: 1px solid #7A5230;">
    <button onclick="document.getElementById('modalLogout').classList.remove('hidden')"
            class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm text-orange-100 hover:text-white w-full transition-colors"
            style="background-color: #7A5230;">
        <span>↪</span><span>Logout</span>
    </button>
</div>

</aside>