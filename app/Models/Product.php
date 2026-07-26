<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'kode_barang',
        'nama_produk',
        'kategori',
        'deskripsi',
        'gambar',
        'harga',
        'stok',
        'minimum_stok',
        'status',
    ];

    // Accessor: biar gampang manggil $product->gambar_url di Blade
    // otomatis dapat URL lengkap, atau gambar default kalau kosong
    public function getGambarUrlAttribute(): string
    {
        if ($this->gambar) {
            return asset('storage/' . $this->gambar);
        }
        return asset('images/no-image.png'); // gambar default, siapkan filenya di public/images/
    }
}