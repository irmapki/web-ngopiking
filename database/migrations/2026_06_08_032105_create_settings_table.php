<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();

            // Pengaturan Pajak
            $table->boolean('ppn_aktif')->default(true);
            $table->integer('diskon_maksimal')->nullable(); // dalam persen
            $table->string('pembulatan_harga')->default('tidak_ada'); // tidak_ada, ke_atas_100, ke_bawah_100, terdekat_100

            // Nomor Dokumen
            $table->string('format_nomor_transaksi')->nullable(); // contoh: DD-MM-YYYY
            $table->string('reset_nomor_urut')->default('bulanan'); // harian, bulanan, tahunan, tidak_reset

            // Printer
            $table->string('ukuran_kertas')->default('thermal_58'); // thermal_58, thermal_80, a4
            $table->integer('margin')->nullable();
            $table->boolean('cetak_otomatis')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};