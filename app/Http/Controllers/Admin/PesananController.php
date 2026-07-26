<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;

class PesananController extends Controller
{
    public function index()
    {
        $pesanan = Transaction::with('details.product')
            ->whereIn('status', ['pending'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $totalPending = Transaction::where('status', 'pending')->count();

        return view('admin.pesanan', compact('pesanan', 'totalPending'));
    }

    public function selesai($id)
    {
        $transaction = Transaction::findOrFail($id);
        $transaction->update(['status' => 'selesai']);
        return back()->with('success', 'Pesanan diselesaikan!');
    }

    public function batal($id)
    {
        $transaction = Transaction::findOrFail($id);
        $transaction->update(['status' => 'batal']);
        return back()->with('success', 'Pesanan dibatalkan!');
    }
    public function struk($id)
{
    $trx = Transaction::with('details.product')->findOrFail($id);
    return view('admin.struk', compact('trx'));
}
}