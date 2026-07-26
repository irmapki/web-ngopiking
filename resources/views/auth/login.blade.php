<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Kasir</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex items-center justify-center" style="background-color: #FBEEE6;">

    <div class="bg-white rounded-2xl shadow-sm p-8 w-full max-w-sm">

        <div class="text-center mb-8">
            <img src="{{ $setting->logo_url }}" alt="Logo {{ $setting->nama_toko ?? 'Toko' }}"
                 class="w-16 h-16 rounded-full object-cover mx-auto mb-3 border border-gray-100">
            <h1 class="text-xl font-bold text-gray-800">{{ $setting->nama_toko ?? 'Ngopi King!' }}</h1>
            <p class="text-sm text-gray-400 mt-1">Log In Untuk Melanjutkan</p>
        </div>

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-600 text-sm rounded-lg px-4 py-3 mb-5">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                <input type="text" name="username" value="{{ old('username') }}"
                    placeholder="Masukkan username"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400"
                    autofocus>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" name="password"
                    placeholder="Masukkan password"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400">
            </div>
            <button type="submit"
                class="w-full text-white font-medium py-2.5 rounded-lg text-sm transition-colors mt-2 hover:opacity-90"
                style="background-color: #8B5E3C;">
                Masuk
            </button>
        </form>

    </div>

</body>
</html>