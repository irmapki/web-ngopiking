<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ngopi King � @yield("title", "Dashboard")</title>
    @vite(["resources/css/app.css", "resources/js/app.js"])
</head>
<body class="bg-gray-100 font-sans">
    <div class="flex h-screen overflow-hidden">
        <x-sidebar />
        <div class="flex flex-col flex-1 overflow-hidden">
            <x-navbar />
            <main class="flex-1 overflow-y-auto p-6">
                @yield("content")
            </main>
        </div>
    </div>
    {{-- Modal Konfirmasi Logout --}}
<div id="modalLogout" class="hidden fixed inset-0 flex items-center justify-center z-50"
     style="background-color: rgba(156,163,175,0.6);">
    <div class="bg-white rounded-2xl p-6 w-full max-w-xs shadow-xl text-center relative">
        <button onclick="document.getElementById('modalLogout').classList.add('hidden')"
                class="absolute top-3 right-4 text-gray-400 hover:text-gray-600 text-xl">✕</button>
        <p class="text-base font-semibold text-gray-800 mb-6 mt-2">Yakin ingin keluar?</p>
        <div class="flex gap-3">
            <form method="POST" action="{{ route('logout') }}" class="flex-1">
                @csrf
                <button type="submit"
                        class="w-full text-white py-2.5 rounded-lg text-sm font-medium"
                        style="background-color: #8B5E3C;">
                    Ya
                </button>
            </form>
            <button onclick="document.getElementById('modalLogout').classList.add('hidden')"
                    class="flex-1 border py-2.5 rounded-lg text-sm font-medium"
                    style="border-color: #8B5E3C; color: #8B5E3C;">
                Tidak
            </button>
        </div>
    </div>
</div>

</body>
</html>

