<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Ubah jadi integer biasa, cukup besar untuk nominal rupiah (bukan cuma persen 0-100 lagi)
            $table->integer('pajak')->default(0)->change();
            $table->integer('diskon')->default(0)->change();
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->tinyInteger('pajak')->default(0)->change();
            $table->tinyInteger('diskon')->default(0)->change();
        });
    }
};