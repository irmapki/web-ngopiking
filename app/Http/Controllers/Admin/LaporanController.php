<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        // Default periode: 7 hari terakhir, kalau user tidak pilih filter
        $dari   = $request->dari ?? now()->subDays(6)->toDateString();
        $sampai = $request->sampai ?? now()->toDateString();

        // Query dasar yang dipakai berkali-kali dengan filter tanggal yang sama
        $baseQuery = fn() => Transaction::whereDate('tanggal', '>=', $dari)
                                         ->whereDate('tanggal', '<=', $sampai);

        // ===== 1. Statistik kartu =====
        $totalTransaksi = $baseQuery()->count();
        $totalPenjualan = $baseQuery()->where('status', 'selesai')->sum('total');
        $qrDebit        = $baseQuery()->whereIn('metode_pembayaran', ['qris', 'debit'])->count();
        $tunai          = $baseQuery()->where('metode_pembayaran', 'tunai')->count();

        // ===== 2. Pendapatan harian (untuk grafik garis) =====
        // GROUP BY tanggal artinya: gabungkan semua transaksi di hari yang sama jadi 1 baris,
        // lalu jumlahkan (SUM) kolom total-nya.
        $pendapatanHarian = $baseQuery()
            ->where('status', 'selesai')
            ->selectRaw('DATE(tanggal) as tgl, SUM(total) as total')
            ->groupBy('tgl')
            ->orderBy('tgl')
            ->get();

        // Chart.js butuh 2 array terpisah: label (sumbu X) dan data (sumbu Y)
        $chartLabels = $pendapatanHarian->map(fn($row) => \Carbon\Carbon::parse($row->tgl)->translatedFormat('d M'));
        $chartData   = $pendapatanHarian->pluck('total');

        // ===== 3. Metode pembayaran (untuk donut chart) =====
        $metodePembayaran = $baseQuery()
            ->selectRaw('metode_pembayaran, COUNT(*) as jumlah')
            ->groupBy('metode_pembayaran')
            ->get();

        // ===== 4. Menu terlaris (untuk progress bar) =====
        $menuTerlaris = TransactionDetail::whereHas('transaction', function ($q) use ($dari, $sampai) {
                $q->whereDate('tanggal', '>=', $dari)->whereDate('tanggal', '<=', $sampai);
            })
            ->with('product')
            ->selectRaw('product_id, SUM(qty) as total_qty')
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->limit(4)
            ->get();

        $maxQty = $menuTerlaris->max('total_qty') ?: 1; // buat hitung persentase lebar bar, hindari bagi 0

        return view('admin.laporan', compact(
            'dari', 'sampai',
            'totalTransaksi', 'totalPenjualan', 'qrDebit', 'tunai',
            'chartLabels', 'chartData',
            'metodePembayaran',
            'menuTerlaris', 'maxQty'
        ));
    }
}