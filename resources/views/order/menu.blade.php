<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ngopi King — Menu</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .menu-card { transition: transform 0.15s ease, box-shadow 0.15s ease; }
        .menu-card:active { transform: scale(0.98); }
        .cat-btn { transition: all 0.15s ease; }
        .add-btn { transition: transform 0.15s ease, background-color 0.15s ease; }
        .add-btn:active { transform: scale(0.85); }
        @keyframes pop {
            0% { transform: scale(1); }
            50% { transform: scale(1.3); }
            100% { transform: scale(1); }
        }
        .pop { animation: pop 0.35s ease; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen pb-28">

    {{-- Header --}}
    <div class="sticky top-0 z-10 px-5 pt-6 pb-4 shadow-sm"
         style="background: linear-gradient(135deg, #8B5E3C, #A8734D);">
        <h1 class="text-2xl font-bold text-white tracking-wide">Ngopi King!</h1>
        <p class="text-sm text-orange-100">Order Now! ☕</p>

        {{-- Search --}}
        <div class="mt-4 relative">
            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm">🔍</span>
            <input type="text" id="searchInput" placeholder="Cari menu favoritmu..."
                   onkeyup="filterMenu()"
                   class="w-full pl-10 pr-4 py-2.5 bg-white rounded-xl text-sm text-gray-700 shadow-sm focus:outline-none focus:ring-2 focus:ring-white/50">
        </div>

        {{-- Filter kategori --}}
        <div class="flex gap-2 mt-3 overflow-x-auto pb-1" style="scrollbar-width: none;">
            <button onclick="filterKategori('semua', this)" id="cat-semua" data-kat="semua"
                    class="cat-btn px-4 py-1.5 rounded-full text-sm font-semibold whitespace-nowrap shadow-sm"
                    style="background-color: white; color: #8B5E3C;">
                Semua produk
            </button>
            @foreach($kategoris as $kat)
            <button onclick="filterKategori('{{ $kat }}', this)" id="cat-{{ Str::slug($kat) }}" data-kat="{{ $kat }}"
                    class="cat-btn px-4 py-1.5 rounded-full text-sm font-medium whitespace-nowrap bg-white/20 text-white border border-white/30 backdrop-blur-sm">
                {{ $kat }}
            </button>
            @endforeach
        </div>
    </div>

    {{-- Grid Produk --}}
    <div class="px-4 pt-4 grid grid-cols-2 gap-3" id="gridMenu">
        @foreach($products as $product)
        <div class="menu-card bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-md"
             data-nama="{{ strtolower($product->nama_produk) }}"
             data-kategori="{{ $product->kategori }}">
            <div class="h-36 bg-gray-100 overflow-hidden relative">
                @if($product->gambar)
                    <img src="{{ asset('storage/' . $product->gambar) }}"
                         alt="{{ $product->nama_produk }}"
                         class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center" style="background: linear-gradient(135deg, #FBEEE6, #F5DEC9);">
                        <span class="text-5xl">🍽️</span>
                    </div>
                @endif
                <span class="absolute top-2 left-2 text-xs font-semibold px-2 py-0.5 rounded-full text-white shadow-sm"
                      style="background-color: rgba(139, 94, 60, 0.85);">
                    {{ $product->kategori }}
                </span>
            </div>
            <div class="p-3">
                <p class="text-sm font-semibold text-gray-800 truncate">{{ $product->nama_produk }}</p>
                <p class="text-sm font-bold mt-0.5" style="color: #8B5E3C;">
                    Rp {{ number_format($product->harga, 0, ',', '.') }}
                </p>
                <div class="flex items-center justify-between mt-2.5">
                    <span class="text-xs text-amber-500 font-medium flex items-center gap-0.5">★ 4.9</span>
                    <button onclick="tambah({{ $product->id }}, this)"
                            class="add-btn w-8 h-8 rounded-full text-white text-lg flex items-center justify-center shadow-sm hover:brightness-110"
                            style="background-color: #8B5E3C;">+</button>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Empty state pencarian --}}
    <div id="emptyState" class="hidden flex-col items-center justify-center gap-2 text-gray-400 py-16">
        <span class="text-4xl">🔍</span>
        <p class="text-sm">Menu tidak ditemukan</p>
    </div>

    {{-- Tombol Lihat Keranjang --}}
    <div class="fixed bottom-0 left-0 right-0 px-4 pb-6 pt-6 pointer-events-none"
         style="background: linear-gradient(to top, #F9FAFB 40%, transparent);">
        <a href="{{ route('order.keranjang') }}"
           class="pointer-events-auto flex items-center justify-center gap-2 w-full py-3.5 rounded-2xl text-white font-semibold shadow-lg hover:shadow-xl transition-shadow"
           style="background: linear-gradient(135deg, #8B5E3C, #A8734D);">
            🛒 Lihat Keranjang
            <span id="totalItemBadge" class="bg-white/25 text-xs font-bold px-2 py-0.5 rounded-full">{{ $totalItem }}</span>
        </a>
    </div>

    <script>
    function tambah(productId, btn) {
        fetch('{{ route("order.tambah") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ product_id: productId })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                const badge = document.getElementById('totalItemBadge');
                badge.textContent = data.total_item;
                badge.classList.remove('pop');
                void badge.offsetWidth; // restart animasi
                badge.classList.add('pop');

                // Flash feedback di tombol +
                btn.textContent = '✓';
                btn.style.backgroundColor = '#16A34A';
                setTimeout(() => {
                    btn.textContent = '+';
                    btn.style.backgroundColor = '#8B5E3C';
                }, 800);
            }
        });
    }

    function filterMenu() {
        const q = document.getElementById('searchInput').value.toLowerCase();
        applyFilters(q, getActiveKategori());
    }

    function filterKategori(kat, btnEl) {
        applyFilters(document.getElementById('searchInput').value.toLowerCase(), kat);

        // Update active button
        document.querySelectorAll('.cat-btn').forEach(btn => {
            btn.style.backgroundColor = '';
            btn.style.color = '';
            btn.classList.add('bg-white/20', 'text-white', 'border', 'border-white/30', 'backdrop-blur-sm');
            btn.classList.remove('shadow-sm');
        });

        btnEl.classList.remove('bg-white/20', 'text-white', 'border', 'border-white/30', 'backdrop-blur-sm');
        btnEl.classList.add('shadow-sm');
        btnEl.style.backgroundColor = 'white';
        btnEl.style.color = '#8B5E3C';
    }

    function getActiveKategori() {
        const activeBtn = document.querySelector('.cat-btn[style*="background-color: white"]');
        return activeBtn ? activeBtn.dataset.kat : 'semua';
    }

    function applyFilters(search, kategori) {
        let visibleCount = 0;
        document.querySelectorAll('.menu-card').forEach(card => {
            const matchSearch = card.dataset.nama.includes(search);
            const matchKategori = (kategori === 'semua' || card.dataset.kategori === kategori);
            const show = matchSearch && matchKategori;
            card.style.display = show ? 'block' : 'none';
            if (show) visibleCount++;
        });

        const emptyState = document.getElementById('emptyState');
        emptyState.style.display = visibleCount === 0 ? 'flex' : 'none';
    }
    </script>
</body>
</html>