<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Paksa perbaiki kolom pajak & diskon di transactions jadi INT biasa
        DB::statement('ALTER TABLE transactions MODIFY pajak INT DEFAULT 0');
        DB::statement('ALTER TABLE transactions MODIFY diskon INT DEFAULT 0');

        // 2. Tambahkan kolom profil toko ke settings, HANYA jika belum ada
        // (supaya migration ini aman dijalankan berkali-kali tanpa error "column already exists")
        if (!Schema::hasColumn('settings', 'nama_toko')) {
            DB::statement("ALTER TABLE settings ADD COLUMN nama_toko VARCHAR(255) NULL AFTER id");
        }
        if (!Schema::hasColumn('settings', 'logo')) {
            DB::statement("ALTER TABLE settings ADD COLUMN logo VARCHAR(255) NULL AFTER nama_toko");
        }
        if (!Schema::hasColumn('settings', 'alamat')) {
            DB::statement("ALTER TABLE settings ADD COLUMN alamat TEXT NULL AFTER logo");
        }
        if (!Schema::hasColumn('settings', 'no_telepon')) {
            DB::statement("ALTER TABLE settings ADD COLUMN no_telepon VARCHAR(255) NULL AFTER alamat");
        }
        if (!Schema::hasColumn('settings', 'email')) {
            DB::statement("ALTER TABLE settings ADD COLUMN email VARCHAR(255) NULL AFTER no_telepon");
        }
        if (!Schema::hasColumn('settings', 'jam_buka')) {
            DB::statement("ALTER TABLE settings ADD COLUMN jam_buka TIME NULL AFTER email");
        }
        if (!Schema::hasColumn('settings', 'jam_tutup')) {
            DB::statement("ALTER TABLE settings ADD COLUMN jam_tutup TIME NULL AFTER jam_buka");
        }
        if (!Schema::hasColumn('settings', 'deskripsi')) {
            DB::statement("ALTER TABLE settings ADD COLUMN deskripsi TEXT NULL AFTER jam_tutup");
        }

        // 3. Tambahkan juga kolom-kolom Struk & Pajak, HANYA jika belum ada
        if (!Schema::hasColumn('settings', 'ppn_aktif')) {
            DB::statement("ALTER TABLE settings ADD COLUMN ppn_aktif TINYINT(1) NOT NULL DEFAULT 1");
        }
        if (!Schema::hasColumn('settings', 'diskon_maksimal')) {
            DB::statement("ALTER TABLE settings ADD COLUMN diskon_maksimal INT NULL");
        }
        if (!Schema::hasColumn('settings', 'pembulatan_harga')) {
            DB::statement("ALTER TABLE settings ADD COLUMN pembulatan_harga VARCHAR(255) NOT NULL DEFAULT 'tidak_ada'");
        }
        if (!Schema::hasColumn('settings', 'format_nomor_transaksi')) {
            DB::statement("ALTER TABLE settings ADD COLUMN format_nomor_transaksi VARCHAR(255) NULL");
        }
        if (!Schema::hasColumn('settings', 'reset_nomor_urut')) {
            DB::statement("ALTER TABLE settings ADD COLUMN reset_nomor_urut VARCHAR(255) NOT NULL DEFAULT 'bulanan'");
        }
        if (!Schema::hasColumn('settings', 'ukuran_kertas')) {
            DB::statement("ALTER TABLE settings ADD COLUMN ukuran_kertas VARCHAR(255) NOT NULL DEFAULT 'thermal_58'");
        }
        if (!Schema::hasColumn('settings', 'margin')) {
            DB::statement("ALTER TABLE settings ADD COLUMN margin INT NULL");
        }
        if (!Schema::hasColumn('settings', 'cetak_otomatis')) {
            DB::statement("ALTER TABLE settings ADD COLUMN cetak_otomatis TINYINT(1) NOT NULL DEFAULT 1");
        }
    }

    public function down(): void
    {
        // Sengaja tidak dibuat rollback otomatis untuk migration darurat ini
        // supaya tidak berisiko menghapus data yang sudah diisi
    }
};