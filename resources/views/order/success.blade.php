<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Berhasil</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center px-5">

    <div class="bg-white rounded-3xl p-8 w-full max-w-sm text-center shadow-lg">

        {{-- Icon success --}}
        <div class="w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-5"
             style="background-color: #8B5E3C;">
            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
            </svg>
        </div>

        <h2 class="text-2xl font-bold text-gray-800 mb-2">Success !</h2>
        <p class="text-sm text-gray-500 mb-1">Pesananmu sedang diproses</p>
        <p class="text-sm text-gray-400 mb-1">oleh kasir kami.</p>
        <p class="text-xs text-gray-400 mt-3">Kode: <span class="font-semibold">{{ $trx->kode_transaksi }}</span></p>

        <a href="{{ route('order.menu') }}"
           class="mt-6 block w-full py-3 rounded-2xl text-white font-semibold"
           style="background-color: #8B5E3C;">
            Go Back
        </a>
    </div>

</body>
</html>