<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk {{ $trx->kode_transaksi }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            color: #111;
            width: 280px;
            margin: 20px auto;
        }
        .center { text-align: center; }
        .bold { font-weight: bold; }
        .divider { border-top: 1px dashed #999; margin: 8px 0; }
        .row { display: flex; justify-content: space-between; gap: 8px; }
        .brand { font-size: 15px; }
        .items .row { margin-bottom: 4px; }
        .item-name { flex: 1; }
        .totals .row { margin-bottom: 2px; }
        .grand { font-size: 13px; margin-top: 4px; }
        .btn-print {
            display: block;
            width: 100%;
            margin-top: 16px;
            padding: 10px;
            border: none;
            border-radius: 6px;
            background-color: #8B5E3C;
            color: white;
            font-family: Arial, sans-serif;
            font-size: 13px;
            cursor: pointer;
        }
        @media print {
            .btn-print { display: none; }
            body { margin: 0; width: 100%; }
        }
    </style>
</head>
<body>

    <div class="center brand bold">Ngopi King!</div>
    <div class="center">Struk Pembayaran</div>

    <div class="divider"></div>

    <div class="row"><span>No. Transaksi</span><span>{{ $trx->kode_transaksi }}</span></div>
    <div class="row"><span>Tanggal</span><span>{{ \Carbon\Carbon::parse($trx->tanggal)->format('d/m/Y H:i') }}</span></div>
    <div class="row"><span>Kasir</span><span>{{ $trx->user->name ?? '-' }}</span></div>

    <div class="divider"></div>

    <div class="items">
        @foreach ($trx->details as $d)
            <div class="row">
                <span class="item-name">{{ $d->product->nama_produk ?? '(dihapus)' }} x{{ $d->qty }}</span>
                <span>Rp {{ number_format($d->subtotal, 0, ',', '.') }}</span>
            </div>
        @endforeach
    </div>

    <div class="divider"></div>

    <div class="totals">
        <div class="row"><span>Sub total</span><span>Rp {{ number_format($trx->subtotal, 0, ',', '.') }}</span></div>
        @if ($trx->diskon > 0)
            <div class="row"><span>Diskon</span><span>- Rp {{ number_format($trx->diskon, 0, ',', '.') }}</span></div>
        @endif
        <div class="row"><span>PPN</span><span>Rp {{ number_format($trx->pajak, 0, ',', '.') }}</span></div>
        <div class="row bold grand"><span>Total</span><span>Rp {{ number_format($trx->total, 0, ',', '.') }}</span></div>
    </div>

    <div class="divider"></div>

    @php
        // Kalau jumlah_bayar kosong ATAU 0 (misal metode non-tunai / data lama),
        // fallback ke $trx->total biar nggak nampilin "Rp 0" yang menyesatkan.
        $jumlahBayarTampil = $trx->jumlah_bayar > 0 ? $trx->jumlah_bayar : $trx->total;
    @endphp
    <div class="row"><span>{{ strtoupper($trx->metode_pembayaran) }}</span><span>Rp {{ number_format($jumlahBayarTampil, 0, ',', '.') }}</span></div>
    @if ($trx->kembalian > 0)
        <div class="row"><span>Kembalian</span><span>Rp {{ number_format($trx->kembalian, 0, ',', '.') }}</span></div>
    @endif

    <div class="divider"></div>

    <div class="center">Terima kasih sudah ngopi!</div>

    <button class="btn-print" onclick="window.print()">🖨️ Cetak</button>

</body>
</html>