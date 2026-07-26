<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        // Profil Toko
        'nama_toko',
        'logo',
        'alamat',
        'no_telepon',
        'email',
        'jam_buka',
        'jam_tutup',
        'deskripsi',

        // Struk & Pajak
        'ppn_aktif',
        'diskon_maksimal',
        'pembulatan_harga',
        'format_nomor_transaksi',
        'reset_nomor_urut',
        'ukuran_kertas',
        'margin',
        'cetak_otomatis',
    ];

    protected $casts = [
        'ppn_aktif'       => 'boolean',
        'cetak_otomatis'  => 'boolean',
    ];

    public static function current(): self
    {
        return static::firstOrCreate(['id' => 1]);
    }

    // Accessor: $setting->logo_url otomatis dapat URL lengkap, atau logo default kalau kosong
    public function getLogoUrlAttribute(): string
    {
        if ($this->logo) {
            return asset('storage/' . $this->logo);
        }
        return asset('images/no-logo.png');
    }
}