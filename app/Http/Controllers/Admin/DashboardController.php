<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;

class DashboardController extends Controller
{
    public function index()
    {
        // ===== 1. Statistik kartu atas =====
        $totalTransaksi = Transaction::count();
        $pending        = Transaction::where('status', 'pending')->count();
        $selesai        = Transaction::where('status', 'selesai')->count();

        // Pendapatan cuma dihitung dari transaksi yang statusnya selesai
        $pendapatan = Transaction::where('status', 'selesai')->sum('total');

        // ===== 2. Tabel pesanan terbaru =====
        // eager load details.product biar nggak kena N+1 query pas nampilin nama item
        $pesananTerbaru = Transaction::with('details.product')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.dashboard', compact(
            'totalTransaksi',
            'pending',
            'selesai',
            'pendapatan',
            'pesananTerbaru'
        ));
    }
}