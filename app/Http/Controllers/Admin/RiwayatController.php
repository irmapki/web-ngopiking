<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class RiwayatController extends Controller
{
    public function index(Request $request)
{
    $query = Transaction::with('details.product')->latest('tanggal');

    if ($request->search) {
        $query->where('kode_transaksi', 'like', '%' . $request->search . '%');
    }

    if ($request->dari && $request->sampai) {
        $query->whereDate('tanggal', '>=', $request->dari)
              ->whereDate('tanggal', '<=', $request->sampai);
    }

    $transactions = $query->paginate(10)->withQueryString();

    // Statistik ikut filter
    $statQuery = Transaction::query();

    if ($request->dari && $request->sampai) {
        $statQuery->whereDate('tanggal', '>=', $request->dari)
                  ->whereDate('tanggal', '<=', $request->sampai);
    }

    if ($request->search) {
        $statQuery->where('kode_transaksi', 'like', '%' . $request->search . '%');
    }

    $totalTransaksi = (clone $statQuery)->count();
    $totalPenjualan = (clone $statQuery)->where('status', 'selesai')->sum('total');

    return view('admin.riwayat', compact('transactions', 'totalTransaksi', 'totalPenjualan'));
}

    // Dipanggil lewat fetch() dari tombol "lihat" (ikon mata) untuk isi modal detail
    public function show(Transaction $transaction)
    {
        $transaction->load('details.product', 'user');

        return response()->json([
            'kode_transaksi'    => $transaction->kode_transaksi,
            'nomor_meja'        => $transaction->nomor_meja ?? 'Manual',
            'nama_pelanggan'    => $transaction->nama_pelanggan,
            'kasir'             => $transaction->user->name ?? '-',
            'tanggal'           => $transaction->tanggal->translatedFormat('d F Y'),
            'jam'               => $transaction->tanggal->format('H:i'),
            'metode_pembayaran' => strtoupper($transaction->metode_pembayaran),
            'status'            => $transaction->status,
            'subtotal'          => $transaction->subtotal,
            'diskon'            => $transaction->diskon,
            'pajak'             => $transaction->pajak,
            'total'             => $transaction->total,
            'jumlah_bayar'      => $transaction->jumlah_bayar,
            'kembalian'         => $transaction->kembalian,
            'items' => $transaction->details->map(function ($d) {
                return [
                    'nama'     => $d->product->nama_produk ?? '(barang dihapus)',
                    'qty'      => $d->qty,
                    'harga'    => $d->harga,
                    'subtotal' => $d->subtotal,
                ];
            }),
        ]);
    }
}