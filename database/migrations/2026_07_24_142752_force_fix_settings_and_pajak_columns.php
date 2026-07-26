<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Paksa perbaiki kolom pajak & diskon di transactions jadi INT biasa
        Schema::table('transactions', function (Blueprint $table) {
            $table->integer('pajak')->default(0)->change();
            $table->integer('diskon')->default(0)->change();
        });

        // 2. Tambahkan kolom profil toko & pengaturan struk/pajak ke settings,
        // HANYA jika belum ada (supaya migration ini aman dijalankan berkali-kali)
        Schema::table('settings', function (Blueprint $table) {
            if (!Schema::hasColumn('settings', 'nama_toko')) {
                $table->string('nama_toko')->nullable()->after('id');
            }
            if (!Schema::hasColumn('settings', 'logo')) {
                $table->string('logo')->nullable()->after('nama_toko');
            }
            if (!Schema::hasColumn('settings', 'alamat')) {
                $table->text('alamat')->nullable()->after('logo');
            }
            if (!Schema::hasColumn('settings', 'no_telepon')) {
                $table->string('no_telepon')->nullable()->after('alamat');
            }
            if (!Schema::hasColumn('settings', 'email')) {
                $table->string('email')->nullable()->after('no_telepon');
            }
            if (!Schema::hasColumn('settings', 'jam_buka')) {
                $table->time('jam_buka')->nullable()->after('email');
            }
            if (!Schema::hasColumn('settings', 'jam_tutup')) {
                $table->time('jam_tutup')->nullable()->after('jam_buka');
            }
            if (!Schema::hasColumn('settings', 'deskripsi')) {
                $table->text('deskripsi')->nullable()->after('jam_tutup');
            }
            if (!Schema::hasColumn('settings', 'ppn_aktif')) {
                $table->boolean('ppn_aktif')->default(true);
            }
            if (!Schema::hasColumn('settings', 'diskon_maksimal')) {
                $table->integer('diskon_maksimal')->nullable();
            }
            if (!Schema::hasColumn('settings', 'pembulatan_harga')) {
                $table->string('pembulatan_harga')->default('tidak_ada');
            }
            if (!Schema::hasColumn('settings', 'format_nomor_transaksi')) {
                $table->string('format_nomor_transaksi')->nullable();
            }
            if (!Schema::hasColumn('settings', 'reset_nomor_urut')) {
                $table->string('reset_nomor_urut')->default('bulanan');
            }
            if (!Schema::hasColumn('settings', 'ukuran_kertas')) {
                $table->string('ukuran_kertas')->default('thermal_58');
            }
            if (!Schema::hasColumn('settings', 'margin')) {
                $table->integer('margin')->nullable();
            }
            if (!Schema::hasColumn('settings', 'cetak_otomatis')) {
                $table->boolean('cetak_otomatis')->default(true);
            }
        });
    }

    public function down(): void
    {
        // Sengaja tidak dibuat rollback otomatis untuk migration darurat ini
        // supaya tidak berisiko menghapus data yang sudah diisi
    }
};
