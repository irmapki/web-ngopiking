<header class="bg-white border-b border-gray-100 px-6 py-4 flex items-center justify-between flex-shrink-0">
    <h2 class="text-base font-semibold text-gray-700">
        @yield('page-title', 'Dashboard')
    </h2>
    <div class="flex items-center gap-5 text-sm text-gray-500">

        <span class="hidden sm:inline">{{ now()->translatedFormat('l, d F Y') }}</span>

        <div class="h-6 w-px bg-gray-200 hidden sm:block"></div>

        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-full flex items-center justify-center text-white text-xs font-bold shrink-0"
                 style="background-color: #8B5E3C;">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
            <div class="flex flex-col leading-tight">
                <span class="text-gray-700 font-medium">{{ Auth::user()->name }}</span>
                <span class="text-xs px-2 py-0.5 rounded-full w-fit font-medium {{ Auth::user()->role === 'admin' ? 'bg-amber-100 text-amber-700' : 'bg-blue-100 text-blue-600' }}">
                    {{ ucfirst(Auth::user()->role) }}
                </span>
            </div>
        </div>

        <div class="h-6 w-px bg-gray-200"></div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="button" onclick="document.getElementById('modalLogout').classList.remove('hidden')"
                    class="flex items-center gap-1.5 text-gray-400 hover:text-red-500 text-xs font-medium transition-colors">
                <span>↪</span>
                <span>Keluar</span>
            </button>
        </form>
    </div>
</header>