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
        Schema::create('t_transaksi', function (Blueprint $table) {
            $table->id('transaksi_id');

            $table->string('kode_invoice', 50)->unique();
            $table->string('nama_customer');
            $table->string('no_telp', 20);
            $table->string('email');
            $table->string('alamat');
            $table->enum('status_transaksi', ['pesanan dibuat', 'dikemas', 'dikirim', 'selesai', 'batal'])->default('pesanan dibuat');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_transaksi');
    }
};
