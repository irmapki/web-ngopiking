<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AkunController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('admin.akun', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required',
            'username' => 'required|unique:users',
            'password' => 'required|min:6',
            'role'     => 'required|in:admin,kasir',
        ]);

        User::create([
            'name'     => $request->name,
            'username' => $request->username,
            'email'    => $request->username . '@ngopking.com',
            'password' => Hash::make($request->password),
            'role'     => $request->role,
        ]);

        return back()->with('success', 'Akun berhasil ditambahkan!');
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'     => 'required',
            'username' => 'required|unique:users,username,' . $user->id,
            'role'     => 'required|in:admin,kasir',
        ]);

        $data = [
            'name'     => $request->name,
            'username' => $request->username,
            'role'     => $request->role,
        ];

        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return back()->with('success', 'Akun berhasil diupdate!');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->withErrors(['error' => 'Tidak bisa hapus akun sendiri!']);
        }
        $user->delete();
        return back()->with('success', 'Akun berhasil dihapus!');
    }
}