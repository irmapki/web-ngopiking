<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('transactions', function (Blueprint $table) {
        $table->id();
        $table->string('kode_transaksi')->unique();
        $table->dateTime('tanggal');
        $table->integer('subtotal');
        $table->integer('diskon')->default(0);
        $table->decimal('pajak', 5, 2)->default(0);
        $table->integer('total');
        $table->enum('metode_pembayaran', ['tunai', 'qris', 'debit']);
        $table->integer('jumlah_bayar')->nullable();
        $table->integer('kembalian')->nullable();
        $table->enum('status', ['pending', 'selesai', 'batal'])->default('pending');
        $table->string('nama_pelanggan')->nullable();
        $table->string('nomor_meja')->nullable();
        $table->enum('sumber', ['manual', 'self_order'])->default('manual');
        $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
        $table->timestamps();
    });
}
};
