<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PengaturanController extends Controller
{
    public function index()
    {
        $setting = Setting::current();
        return view('admin.pengaturan', compact('setting'));
    }

    public function updateProfil(Request $request)
    {
        $request->validate([
            'nama_toko'  => 'required|string|max:100',
            'alamat'     => 'nullable|string',
            'no_telepon' => 'nullable|string|max:20',
            'email'      => 'nullable|email|max:100',
            'jam_buka'   => 'nullable',
            'jam_tutup'  => 'nullable',
            'deskripsi'  => 'nullable|string|max:500',
            'logo'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $setting = Setting::current();
        $logoPath = $setting->logo; // default: tetap logo lama

        if ($request->hasFile('logo')) {
            // Hapus logo lama dulu (kalau ada) biar tidak numpuk file sampah
            if ($setting->logo) {
                Storage::disk('public')->delete($setting->logo);
            }
            $logoPath = $request->file('logo')->store('logo', 'public');
        }

        $setting->update([
            'nama_toko'  => $request->nama_toko,
            'logo'       => $logoPath,
            'alamat'     => $request->alamat,
            'no_telepon' => $request->no_telepon,
            'email'      => $request->email,
            'jam_buka'   => $request->jam_buka,
            'jam_tutup'  => $request->jam_tutup,
            'deskripsi'  => $request->deskripsi,
        ]);

        return back()->with('success', 'Profil toko berhasil disimpan!');
    }

    public function updatePajak(Request $request)
    {
        $request->validate([
            'ppn_aktif'        => 'nullable|boolean',
            'diskon_maksimal'  => 'nullable|integer|min:0|max:100',
            'pembulatan_harga' => 'required|in:tidak_ada,ke_atas_100,ke_bawah_100,terdekat_100',
        ]);

        $setting = Setting::current();
        $setting->update([
            'ppn_aktif'        => $request->has('ppn_aktif'),
            'diskon_maksimal'  => $request->diskon_maksimal,
            'pembulatan_harga' => $request->pembulatan_harga,
        ]);

        return back()->with('success', 'Pengaturan pajak berhasil disimpan!');
    }

    public function updateNomor(Request $request)
    {
        $request->validate([
            'format_nomor_transaksi' => 'nullable|string|max:50',
            'reset_nomor_urut'       => 'required|in:harian,bulanan,tahunan,tidak_reset',
        ]);

        $setting = Setting::current();
        $setting->update([
            'format_nomor_transaksi' => $request->format_nomor_transaksi,
            'reset_nomor_urut'       => $request->reset_nomor_urut,
        ]);

        return back()->with('success', 'Format nomor dokumen berhasil disimpan!');
    }

    public function updatePrinter(Request $request)
    {
        $request->validate([
            'ukuran_kertas'   => 'required|in:thermal_58,thermal_80,a4',
            'margin'          => 'nullable|integer|min:0',
            'cetak_otomatis'  => 'nullable|boolean',
        ]);

        $setting = Setting::current();
        $setting->update([
            'ukuran_kertas'  => $request->ukuran_kertas,
            'margin'         => $request->margin,
            'cetak_otomatis' => $request->has('cetak_otomatis'),
        ]);

        return back()->with('success', 'Pengaturan printer berhasil disimpan!');
    }
}