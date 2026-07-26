<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'kode_transaksi',
        'tanggal',
        'subtotal',
        'diskon',
        'pajak',
        'total',
        'metode_pembayaran',
        'jumlah_bayar',
        'kembalian',
        'status',
        'nama_pelanggan',
        'nomor_meja',
        'sumber',
        'user_id',
    ];

    protected $casts = [
        'tanggal' => 'datetime',
    ];

    public function details()
    {
        return $this->hasMany(TransactionDetail::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}