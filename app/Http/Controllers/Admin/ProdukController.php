<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProdukController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        if ($request->search) {
            $query->where('nama_produk', 'like', '%' . $request->search . '%')
                  ->orWhere('kode_barang', 'like', '%' . $request->search . '%');
        }

        if ($request->kategori && $request->kategori !== 'semua') {
            $query->where('kategori', $request->kategori);
        }

        $products  = $query->paginate(10);
        $stokMinim = Product::whereColumn('stok', '<', 'minimum_stok')->count();
        $kategoris = Product::distinct()->pluck('kategori');

        return view('admin.produk', compact('products', 'stokMinim', 'kategoris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_barang'  => 'required|unique:products',
            'nama_produk'  => 'required',
            'kategori'     => 'required',
            'harga'        => 'required|numeric',
            'stok'         => 'required|integer',
            'minimum_stok' => 'required|integer',
            'gambar'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048', // max 2MB
        ]);

        $path = null;
        if ($request->hasFile('gambar')) {
            // Simpan ke storage/app/public/products, nama file otomatis unik
            $path = $request->file('gambar')->store('products', 'public');
        }

        Product::create([
            'kode_barang'  => $request->kode_barang,
            'nama_produk'  => $request->nama_produk,
            'kategori'     => $request->kategori,
            'deskripsi'    => $request->deskripsi,
            'gambar'       => $path,
            'harga'        => str_replace('.', '', $request->harga),
            'stok'         => $request->stok,
            'minimum_stok' => $request->minimum_stok,
            'status'       => $request->has('status') ? 1 : 0,
        ]);

        return back()->with('success', 'Barang berhasil ditambahkan!');
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'kode_barang'  => 'required|unique:products,kode_barang,' . $product->id,
            'nama_produk'  => 'required',
            'kategori'     => 'required',
            'harga'        => 'required|integer',
            'stok'         => 'required|integer',
            'minimum_stok' => 'required|integer',
            'gambar'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $path = $product->gambar; // default: tetap gambar lama

        if ($request->hasFile('gambar')) {
            // Hapus gambar lama dulu (kalau ada) biar tidak numpuk file sampah
            if ($product->gambar) {
                Storage::disk('public')->delete($product->gambar);
            }
            $path = $request->file('gambar')->store('products', 'public');
        }

        $product->update([
            'kode_barang'  => $request->kode_barang,
            'nama_produk'  => $request->nama_produk,
            'kategori'     => $request->kategori,
            'deskripsi'    => $request->deskripsi,
            'gambar'       => $path,
            'harga'        => $request->harga,
            'stok'         => $request->stok,
            'minimum_stok' => $request->minimum_stok,
            'status'       => $request->has('status') ? 1 : 0,
        ]);

        return back()->with('success', 'Barang berhasil diupdate!');
    }

    public function destroy(Product $product)
    {
        // Hapus file gambar juga saat barangnya dihapus
        if ($product->gambar) {
            Storage::disk('public')->delete($product->gambar);
        }
        $product->delete();
        return back()->with('success', 'Barang berhasil dihapus!');
    }
}