<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InputManualController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::where('status', 1);

        if ($request->search) {
            $query->where('nama_produk', 'like', '%' . $request->search . '%');
        }

        if ($request->kategori && $request->kategori !== 'semua') {
            $query->where('kategori', $request->kategori);
        }

        $products  = $query->get();
        $kategoris = Product::where('status', 1)->distinct()->pluck('kategori');
        $setting   = Setting::current();

        return view('admin.input-manual', compact('products', 'kategoris', 'setting'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'items'              => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.qty'        => 'required|integer|min:1',
            'metode_pembayaran'  => 'required|in:tunai,qris,debit',
            'jumlah_bayar'       => 'nullable|integer',
            'diskon_persen'      => 'nullable|numeric|min:0|max:100',
            'pajak_persen'       => 'nullable|numeric|min:0',
            'nomor_meja'         => 'nullable|string|max:20',
        ]);

        $setting = Setting::current();

        DB::beginTransaction();
        try {
            $subtotal = 0;
            $items    = [];

            foreach ($request->items as $item) {
                $product   = Product::findOrFail($item['product_id']);
                $itemTotal = $product->harga * $item['qty'];
                $subtotal += $itemTotal;

                $items[] = [
                    'product'  => $product,
                    'qty'      => $item['qty'],
                    'harga'    => $product->harga,
                    'subtotal' => $itemTotal,
                ];
            }

            // ===== Validasi Diskon Maksimal dari Pengaturan =====
            $diskonPersen = $request->diskon_persen ?? 0;
            if ($setting->diskon_maksimal !== null && $diskonPersen > $setting->diskon_maksimal) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => "Diskon melebihi batas maksimal ({$setting->diskon_maksimal}%) yang diatur di Pengaturan.",
                ], 422);
            }

            // Diskon disimpan sebagai NOMINAL (rupiah)
            $diskon = round($subtotal * $diskonPersen / 100);

            // Pajak: dihormati sesuai toggle per-transaksi yang dikirim kasir (bukan dipaksa dari setting global)
            $pajakPersen = $request->boolean('ppn_aktif') ? ($request->pajak_persen ?? 0) : 0;
            $pajak       = round(($subtotal - $diskon) * $pajakPersen / 100);

            $total = $subtotal - $diskon + $pajak;

            // ===== Terapkan Pembulatan Harga dari Pengaturan =====
            $total = $this->terapkanPembulatan($total, $setting->pembulatan_harga);

            // Generate kode transaksi
            $kode = 'TRX-' . date('dmy') . '-' . str_pad(
                Transaction::whereDate('created_at', today())->count() + 1, 3, '0', STR_PAD_LEFT
            );

            $transaction = Transaction::create([
                'kode_transaksi'    => $kode,
                'tanggal'           => now(),
                'subtotal'          => $subtotal,
                'diskon'            => $diskon,
                'pajak'             => $pajak, // nominal rupiah, konsisten dengan kolom diskon
                'total'             => $total,
                'metode_pembayaran' => $request->metode_pembayaran,
                'jumlah_bayar'      => $request->jumlah_bayar,
                'kembalian'         => $request->jumlah_bayar ? $request->jumlah_bayar - $total : 0,
                'status'            => 'pending',
                'sumber'            => 'manual',
                'nomor_meja'        => $request->nomor_meja,
                'user_id'           => Auth::id(),
            ]);

            foreach ($items as $item) {
                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id'     => $item['product']->id,
                    'qty'            => $item['qty'],
                    'harga'          => $item['harga'],
                    'subtotal'       => $item['subtotal'],
                ]);

                // Kurangi stok
                $item['product']->decrement('stok', $item['qty']);
            }

            DB::commit();

            return response()->json([
                'success'        => true,
                'kode_transaksi' => $kode,
                'transaction_id' => $transaction->id,
                'total'          => $total,
                'kembalian'      => $request->jumlah_bayar ? $request->jumlah_bayar - $total : 0,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // Terapkan aturan pembulatan sesuai Pengaturan > Struk & Pajak
    private function terapkanPembulatan(int $total, ?string $mode): int
    {
        return match ($mode) {
            'ke_atas_100'  => (int) (ceil($total / 100) * 100),
            'ke_bawah_100' => (int) (floor($total / 100) * 100),
            'terdekat_100' => (int) (round($total / 100) * 100),
            default        => $total, // 'tidak_ada' atau null
        };
    }
}