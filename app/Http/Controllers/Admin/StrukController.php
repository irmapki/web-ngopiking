<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;

class StrukController extends Controller
{
    public function show($id)
    {
        $trx = Transaction::with(['details.product', 'user'])->findOrFail($id);

        return view('admin.struk', compact('trx'));
    }
}