@extends('layouts.app')
@section('title', 'Kelola Akun')
@section('page-title', 'Kelola Akun')

@section('content')

@if(session('success'))
<div class="bg-green-50 border border-green-200 text-green-700 text-sm rounded-xl px-5 py-3 mb-4">
    {{ session('success') }}
</div>
@endif

<div class="flex justify-between items-center mb-4">
    <p class="text-sm text-gray-500">Kelola akun admin dan kasir</p>
    <button onclick="document.getElementById('modalTambah').classList.remove('hidden')"
            class="text-white text-sm px-4 py-2 rounded-lg"
            style="background-color: #8B5E3C;">
        + Tambah Akun
    </button>
</div>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-gray-100">
                <th class="text-left px-5 py-3 text-gray-500 font-medium">Nama</th>
                <th class="text-left px-5 py-3 text-gray-500 font-medium">Username</th>
                <th class="text-left px-5 py-3 text-gray-500 font-medium">Role</th>
                <th class="text-left px-5 py-3 text-gray-500 font-medium">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
            <tr class="border-b border-gray-50 hover:bg-gray-50">
                <td class="px-5 py-3 font-medium text-gray-800">{{ $user->name }}</td>
                <td class="px-5 py-3 text-gray-600">{{ $user->username }}</td>
                <td class="px-5 py-3">
                    <span class="px-3 py-1 rounded-full text-xs font-medium
                        {{ $user->role === 'admin' ? 'bg-amber-100 text-amber-700' : 'bg-blue-100 text-blue-600' }}">
                        {{ ucfirst($user->role) }}
                    </span>
                </td>
                <td class="px-5 py-3">
                    <div class="flex items-center gap-2">
                        <button onclick="openEdit({{ $user }})" class="text-blue-400 hover:text-blue-600">✏️</button>
                        @if($user->id !== auth()->id())
                        <form method="POST" action="{{ route('admin.akun.destroy', $user) }}"
                              onsubmit="return confirm('Hapus akun ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-400 hover:text-red-600">🗑️</button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="px-5 py-8 text-center text-gray-400">Belum ada akun</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Modal Tambah --}}
<div id="modalTambah" class="hidden fixed inset-0 flex items-center justify-center z-50" style="background-color: rgba(0,0,0,0.5);">
    <div class="bg-white rounded-2xl p-6 w-full max-w-md shadow-xl">
        <div class="flex items-center justify-between mb-5">
            <h3 class="text-base font-semibold text-gray-800">Tambah Akun</h3>
            <button onclick="document.getElementById('modalTambah').classList.add('hidden')"
                    class="text-gray-400 hover:text-gray-600 text-xl">✕</button>
        </div>
        <form method="POST" action="{{ route('admin.akun') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama lengkap</label>
                <input type="text" name="name" placeholder="Contoh: Budi Kasir"
                       class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                <input type="text" name="username" placeholder="Contoh: budi123"
                       class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" name="password" placeholder="Minimal 6 karakter"
                       class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                <select name="role" class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400">
                    <option value="kasir">Kasir</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button"
                        onclick="document.getElementById('modalTambah').classList.add('hidden')"
                        class="flex-1 border border-gray-200 text-gray-600 py-2.5 rounded-lg text-sm hover:bg-gray-50">
                    Batal
                </button>
                <button type="submit"
                        class="flex-1 text-white py-2.5 rounded-lg text-sm font-medium"
                        style="background-color: #8B5E3C;">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Edit --}}
<div id="modalEdit" class="hidden fixed inset-0 flex items-center justify-center z-50" style="background-color: rgba(0,0,0,0.5);">
    <div class="bg-white rounded-2xl p-6 w-full max-w-md shadow-xl">
        <div class="flex items-center justify-between mb-5">
            <h3 class="text-base font-semibold text-gray-800">Edit Akun</h3>
            <button onclick="document.getElementById('modalEdit').classList.add('hidden')"
                    class="text-gray-400 hover:text-gray-600 text-xl">✕</button>
        </div>
        <form method="POST" id="formEdit" class="space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama lengkap</label>
                <input type="text" name="name" id="edit_name"
                       class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                <input type="text" name="username" id="edit_username"
                       class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password baru (kosongkan jika tidak diubah)</label>
                <input type="password" name="password" placeholder="Kosongkan jika tidak diubah"
                       class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                <select name="role" id="edit_role" class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400">
                    <option value="kasir">Kasir</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button"
                        onclick="document.getElementById('modalEdit').classList.add('hidden')"
                        class="flex-1 border border-gray-200 text-gray-600 py-2.5 rounded-lg text-sm hover:bg-gray-50">
                    Batal
                </button>
                <button type="submit"
                        class="flex-1 text-white py-2.5 rounded-lg text-sm font-medium"
                        style="background-color: #8B5E3C;">
                    Update
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openEdit(user) {
    document.getElementById('edit_name').value = user.name;
    document.getElementById('edit_username').value = user.username;
    document.getElementById('edit_role').value = user.role;
    document.getElementById('formEdit').action = '/admin/akun/' + user.id;
    document.getElementById('modalEdit').classList.remove('hidden');
}
</script>

@endsection