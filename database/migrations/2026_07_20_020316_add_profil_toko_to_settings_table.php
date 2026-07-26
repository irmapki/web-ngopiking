<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('nama_toko')->nullable()->after('id');
            $table->string('logo')->nullable()->after('nama_toko');
            $table->text('alamat')->nullable()->after('logo');
            $table->string('no_telepon')->nullable()->after('alamat');
            $table->string('email')->nullable()->after('no_telepon');
            $table->time('jam_buka')->nullable()->after('email');
            $table->time('jam_tutup')->nullable()->after('jam_buka');
            $table->text('deskripsi')->nullable()->after('jam_tutup');
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn([
                'nama_toko', 'logo', 'alamat', 'no_telepon',
                'email', 'jam_buka', 'jam_tutup', 'deskripsi',
            ]);
        });
    }
};