<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bayar QRIS</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white min-h-screen px-5 pt-8 pb-24">

    <h1 class="text-2xl font-bold text-gray-800">Ngopi King!</h1>
    <p class="text-sm text-gray-400 mb-6">Bayar Pesanan Anda</p>

    {{-- QR Code --}}
    <div class="flex flex-col items-center mb-6">
        <div class="border-4 border-gray-800 rounded-xl p-3 mb-4">
            {{-- Ganti src dengan gambar QRIS toko kamu --}}
            <img src="{{ asset('images/qris.png') }}" alt="QRIS" class="w-48 h-48 object-contain"
                 onerror="this.src='https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=QRIS-NGOPKING'">
        </div>
        <p class="text-sm font-semibold text-gray-700">Total: Rp {{ number_format($trx->total, 0, ',', '.') }}</p>
    </div>

    {{-- Instruksi --}}
    <div class="space-y-2 text-sm text-gray-600">
        <p>1. Buka aplikasi e-wallet atau mobile banking yang mendukung QRIS, seperti ShopeePay, DANA, OVO, GoPay, atau m-banking lainnya.</p>
        <p>2. Sistem akan menampilkan kode QR yang dapat digunakan untuk melakukan pembayaran.</p>
        <p>3. Pilih menu "Scan QR" atau "Bayar dengan QRIS", lalu arahkan kamera ke kode QR yang ditampilkan.</p>
        <p>4. Pastikan nama merchant dan nominal pembayaran sudah sesuai.</p>
        <p>5. Konfirmasi pembayaran dengan memasukkan PIN atau verifikasi sidik jari.</p>
        <p>6. Setelah pembayaran berhasil, sistem akan otomatis memverifikasi transaksi dan status pesanan berubah menjadi "Pembayaran Berhasil".</p>
    </div>

    {{-- Tombol Selesai --}}
    <div class="fixed bottom-0 left-0 right-0 px-5 pb-6 pt-2 bg-white">
        <a href="{{ route('order.success', $trx->kode_transaksi) }}"
           class="block w-full py-3.5 rounded-2xl text-center font-semibold border-2"
           style="border-color: #8B5E3C; color: #8B5E3C;">
            Selesai
        </a>
    </div>

</body>
</html>