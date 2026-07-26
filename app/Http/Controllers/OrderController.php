<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    // Splash screen → redirect ke menu
    public function index(Request $request)
    {
        $table = $request->query('table', '');
        session(['nomor_meja' => $table]);
        return view('order.splash', compact('table'));
    }

    // Halaman menu
    public function menu(Request $request)
    {
        $query = Product::where('status', 1)->where('stok', '>', 0);

        if ($request->search) {
            $query->where('nama_produk', 'like', '%' . $request->search . '%');
        }

        if ($request->kategori && $request->kategori !== 'semua') {
            $query->where('kategori', $request->kategori);
        }

        $products  = $query->get();
        $kategoris = Product::where('status', 1)->distinct()->pluck('kategori');
        $keranjang = session('keranjang', []);
        $totalItem = array_sum(array_column($keranjang, 'qty'));
        $table     = session('nomor_meja', $request->query('table', ''));

        return view('order.menu', compact('products', 'kategoris', 'keranjang', 'totalItem', 'table'));
    }

    // Tambah ke keranjang
    public function tambah(Request $request)
    {
        $product = Product::findOrFail($request->product_id);
        $keranjang = session('keranjang', []);

        $key = $request->product_id;
        if (isset($keranjang[$key])) {
            $keranjang[$key]['qty']++;
        } else {
            $keranjang[$key] = [
                'product_id' => $product->id,
                'nama'       => $product->nama_produk,
                'harga'      => $product->harga,
                'qty'        => 1,
            ];
        }

        session(['keranjang' => $keranjang]);
        return response()->json(['success' => true, 'total_item' => array_sum(array_column($keranjang, 'qty'))]);
    }

    // Update qty
    public function update(Request $request)
    {
        $keranjang = session('keranjang', []);
        $key = $request->product_id;

        if (isset($keranjang[$key])) {
            if ($request->qty <= 0) {
                unset($keranjang[$key]);
            } else {
                $keranjang[$key]['qty'] = $request->qty;
            }
        }

        session(['keranjang' => $keranjang]);
        return response()->json(['success' => true]);
    }

    // Hapus item
    public function hapus(Request $request)
    {
        $keranjang = session('keranjang', []);
        unset($keranjang[$request->product_id]);
        session(['keranjang' => $keranjang]);
        return response()->json(['success' => true]);
    }

    // Halaman keranjang
    public function keranjang()
    {
        $keranjang = session('keranjang', []);
        $total     = array_sum(array_map(fn($i) => $i['harga'] * $i['qty'], $keranjang));
        $table     = session('nomor_meja', '');
        return view('order.keranjang', compact('keranjang', 'total', 'table'));
    }

    // Halaman checkout
    public function checkout()
    {
        $keranjang = session('keranjang', []);
        if (empty($keranjang)) return redirect()->route('order.menu');

        $total = array_sum(array_map(fn($i) => $i['harga'] * $i['qty'], $keranjang));
        $table = session('nomor_meja', '');
        return view('order.checkout', compact('keranjang', 'total', 'table'));
    }

    // Simpan transaksi
    public function store(Request $request)
    {
        $request->validate([
            'nama'    => 'required',
            'email'   => 'required|email',
            'meja'    => 'required',
            'metode'  => 'required|in:tunai,qris',
        ]);

        $keranjang = session('keranjang', []);
        if (empty($keranjang)) return redirect()->route('order.menu');

        DB::beginTransaction();
        try {
            $subtotal = array_sum(array_map(fn($i) => $i['harga'] * $i['qty'], $keranjang));
            $catatan  = $request->catatan;

            $kode = 'ORD-' . date('dmy') . '-' . str_pad(
                Transaction::whereDate('created_at', today())->count() + 1, 3, '0', STR_PAD_LEFT
            );

            $transaction = Transaction::create([
                'kode_transaksi'    => $kode,
                'tanggal'           => now(),
                'subtotal'          => $subtotal,
                'diskon'            => 0,
                'pajak'             => 0,
                'total'             => $subtotal,
                'metode_pembayaran' => $request->metode,
                'status'            => 'pending',
                'nama_pelanggan'    => $request->nama,
                'nomor_meja'        => $request->meja,
                'sumber'            => 'self_order',
            ]);

            foreach ($keranjang as $item) {
                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id'     => $item['product_id'],
                    'qty'            => $item['qty'],
                    'harga'          => $item['harga'],
                    'subtotal'       => $item['harga'] * $item['qty'],
                ]);
            }

            DB::commit();
            session()->forget('keranjang');

            if ($request->metode === 'qris') {
                return redirect()->route('order.qris', $kode);
            }

            return redirect()->route('order.success', $kode);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal menyimpan pesanan: ' . $e->getMessage()]);
        }
    }

    // Halaman QRIS
    public function qris($kode)
    {
        $trx = Transaction::where('kode_transaksi', $kode)->firstOrFail();
        return view('order.qris', compact('trx'));
    }

    // Konfirmasi QRIS selesai
    public function qrisSelesai($kode)
    {
        $trx = Transaction::where('kode_transaksi', $kode)->firstOrFail();
        return redirect()->route('order.success', $kode);
    }

    // Halaman success
    public function success($kode)
    {
        $trx = Transaction::with('details.product')
            ->where('kode_transaksi', $kode)
            ->firstOrFail();
        return view('order.success', compact('trx'));
    }
}