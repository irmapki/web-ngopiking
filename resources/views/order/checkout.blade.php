<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white min-h-screen pb-28">

    <div class="px-5 pt-8 pb-4">
        <h1 class="text-2xl font-bold text-gray-800">Ngopi King!</h1>
        <p class="text-sm text-gray-400">Pay Now</p>
    </div>

    <form method="POST" action="{{ route('order.store') }}" class="px-5 space-y-4">
        @csrf

        <div>
            <p class="font-semibold text-gray-700 mb-3">Masukan Detail Anda</p>

            <div class="space-y-3">
                <div>
                    <label class="text-sm text-gray-600">Nama <span class="text-red-500">*</span></label>
                    <input type="text" name="nama" placeholder="Contoh: Ngopi king!"
                           class="w-full mt-1 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400">
                </div>
                <div>
                    <label class="text-sm text-gray-600">Alamat email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" placeholder="Contoh: email@ngopking.com"
                           class="w-full mt-1 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400">
                </div>
                <div>
                    <label class="text-sm text-gray-600">Nomor Meja <span class="text-red-500">*</span></label>
                    <input type="text" name="meja" value="{{ $table }}" placeholder="Contoh: 10"
                           class="w-full mt-1 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400">
                </div>
            </div>
        </div>

        {{-- Pesanan --}}
        <div>
            <p class="font-semibold text-gray-700 mb-3">Pesanan Anda</p>
            <div class="space-y-2">
                @foreach($keranjang as $item)
                <div class="flex justify-between text-sm text-gray-600">
                    <span>{{ $item['nama'] }} x{{ $item['qty'] }}</span>
                    <span>Rp {{ number_format($item['harga'] * $item['qty'], 0, ',', '.') }}</span>
                </div>
                @endforeach
            </div>
            <div class="flex justify-between font-bold text-gray-800 mt-3 pt-3 border-t border-gray-100">
                <span>Total:</span>
                <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
            </div>
        </div>

        {{-- Catatan --}}
        <div>
            <label class="text-sm text-gray-600">Catatan</label>
            <input type="text" name="catatan" placeholder="Contoh: Less sugar"
                   class="w-full mt-1 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400">
        </div>

        {{-- Metode Pembayaran --}}
        <div>
            <p class="font-semibold text-gray-700 mb-3">Metode Pembayaran</p>
            <div class="space-y-3">
                <label class="flex items-center gap-4 border border-gray-200 rounded-xl px-4 py-3 cursor-pointer"
                       id="label-tunai" style="border-color: #8B5E3C;">
                    <span class="text-xl">🤝</span>
                    <span class="text-sm font-medium text-gray-700 flex-1">Tunai</span>
                    <input type="radio" name="metode" value="tunai" checked
                           onchange="updateMetode()" class="accent-amber-700">
                </label>
                <label class="flex items-center gap-4 border border-gray-200 rounded-xl px-4 py-3 cursor-pointer"
                       id="label-qris">
                    <span class="font-bold text-sm" style="color: #8B5E3C;">QRIS</span>
                    <span class="text-sm font-medium text-gray-700 flex-1">QRIS</span>
                    <input type="radio" name="metode" value="qris"
                           onchange="updateMetode()" class="accent-amber-700">
                </label>
            </div>
        </div>

        {{-- Tombol Bayar --}}
        <div class="fixed bottom-0 left-0 right-0 px-5 pb-6 pt-2 bg-white border-t border-gray-100">
            <button type="submit"
                    class="w-full py-3.5 rounded-2xl text-white font-semibold"
                    style="background-color: #8B5E3C;">
                Bayar Sekarang
            </button>
        </div>

    </form>

    <script>
    function updateMetode() {
        const tunai = document.querySelector('input[value="tunai"]').checked;
        document.getElementById('label-tunai').style.borderColor = tunai ? '#8B5E3C' : '#e5e7eb';
        document.getElementById('label-qris').style.borderColor  = !tunai ? '#8B5E3C' : '#e5e7eb';
    }
    </script>
</body>
</html>