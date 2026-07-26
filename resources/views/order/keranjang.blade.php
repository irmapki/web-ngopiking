<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Saya</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .qty-btn { transition: transform 0.15s ease, background-color 0.15s ease; }
        .qty-btn:active { transform: scale(0.85); }
        .cart-item { transition: opacity 0.2s ease, transform 0.2s ease; }
        .cart-item.removing { opacity: 0; transform: translateX(20px); }
    </style>
</head>
<body class="bg-gray-50 min-h-screen pb-32">

    {{-- Header --}}
    <div class="px-5 pt-8 pb-6 shadow-sm" style="background: linear-gradient(135deg, #8B5E3C, #A8734D);">
        <h1 class="text-2xl font-bold text-white tracking-wide">Ngopi King!</h1>
        <p class="text-sm text-orange-100 mt-0.5">🛒 Keranjang Saya</p>
    </div>

    <div class="px-5 pt-5 space-y-3" id="keranjangList">
        @forelse($keranjang as $key => $item)
        <div class="cart-item flex items-center gap-4 bg-white rounded-2xl p-4 shadow-sm" data-key="{{ $key }}">
            <div class="w-14 h-14 rounded-xl flex items-center justify-center text-2xl flex-shrink-0"
                 style="background: linear-gradient(135deg, #FBEEE6, #F5DEC9);">
                🍽️
            </div>
            <div class="flex-1 min-w-0">
                <p class="font-semibold text-gray-800 text-sm truncate">{{ $item['nama'] }}</p>
                <p class="text-sm font-medium mt-0.5" style="color: #8B5E3C;">Rp {{ number_format($item['harga'], 0, ',', '.') }}</p>
            </div>
            <div class="flex items-center gap-2 flex-shrink-0">
                <button onclick="ubahQty('{{ $key }}', -1, this)"
                        class="qty-btn w-7 h-7 rounded-full bg-gray-100 text-gray-600 text-sm font-bold flex items-center justify-center hover:bg-gray-200">−</button>
                <span class="text-sm font-semibold text-gray-800 w-5 text-center" id="qty-{{ $key }}">{{ $item['qty'] }}</span>
                <button onclick="ubahQty('{{ $key }}', 1, this)"
                        class="qty-btn w-7 h-7 rounded-full text-white text-sm font-bold flex items-center justify-center"
                        style="background-color: #8B5E3C;">+</button>
            </div>
        </div>
        @empty
        <div class="text-center py-20 text-gray-400">
            <p class="text-5xl mb-3">🛒</p>
            <p class="font-medium">Keranjang kosong</p>
            <p class="text-sm mt-1">Yuk pilih menu favoritmu dulu</p>
            <a href="{{ route('order.menu') }}"
               class="inline-flex items-center gap-1.5 text-sm font-semibold mt-4 px-5 py-2.5 rounded-xl text-white"
               style="background: linear-gradient(135deg, #8B5E3C, #A8734D);">
                ← Kembali ke menu
            </a>
        </div>
        @endforelse
    </div>

    @if(!empty($keranjang))
    {{-- Ringkasan --}}
    <div class="px-5 mt-6">
        <div class="bg-white rounded-2xl shadow-sm p-4 space-y-2">
            <div class="flex justify-between text-sm text-gray-500">
                <span>Jumlah item</span>
                <span id="totalQtyText">{{ collect($keranjang)->sum('qty') }} item</span>
            </div>
            <div class="border-t border-gray-100 pt-2 flex justify-between items-center">
                <span class="text-base font-bold text-gray-800">Total</span>
                <span class="text-lg font-bold" style="color: #8B5E3C;" id="totalHargaText">Rp {{ number_format($total, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>
    @endif

    {{-- Tombol Checkout --}}
    @if(!empty($keranjang))
    <div class="fixed bottom-0 left-0 right-0 px-5 pb-6 pt-4" style="background: linear-gradient(to top, #F9FAFB 60%, transparent);">
        <a href="{{ route('order.checkout') }}"
           id="checkoutBtn"
           class="flex items-center justify-center gap-2 w-full py-3.5 rounded-2xl text-center font-semibold text-white shadow-lg hover:shadow-xl transition-shadow"
           style="background: linear-gradient(135deg, #8B5E3C, #A8734D);">
            Checkout Sekarang →
        </a>
    </div>
    @endif

    <script>
    function ubahQty(key, delta, btn) {
        const qtyEl = document.getElementById('qty-' + key);
        const qtyBaru = parseInt(qtyEl.textContent) + delta;

        if (qtyBaru <= 0) {
            hapusItem(key);
            return;
        }

        btn.disabled = true;

        fetch('{{ route("order.update") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ key: key, qty: qtyBaru })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                qtyEl.textContent = qtyBaru;
                updateRingkasan(data.total, data.total_item);
            }
        })
        .finally(() => { btn.disabled = false; });
    }

    function hapusItem(key) {
        const card = document.querySelector('.cart-item[data-key="' + key + '"]');

        fetch('{{ route("order.hapus") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ key: key })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                card.classList.add('removing');
                setTimeout(() => {
                    card.remove();
                    updateRingkasan(data.total, data.total_item);
                    if (data.total_item === 0) {
                        window.location.reload();
                    }
                }, 200);
            }
        });
    }

    function updateRingkasan(total, totalItem) {
        const totalHarga = document.getElementById('totalHargaText');
        const totalQty = document.getElementById('totalQtyText');
        if (totalHarga) totalHarga.textContent = 'Rp ' + total.toLocaleString('id-ID');
        if (totalQty) totalQty.textContent = totalItem + ' item';
    }
    </script>

</body>
</html>