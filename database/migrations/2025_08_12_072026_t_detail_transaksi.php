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
        Schema::create('t_detail_transaksi', function (Blueprint $table) {
            $table->id('detail_transaksi_id');

            // Relasi ke tabel lain
            $table->unsignedBigInteger('transaksi_id');
            $table->unsignedBigInteger('pembayaran_id');
            $table->unsignedBigInteger('produk_id');
            $table->unsignedBigInteger('ukuran_id');
            $table->unsignedBigInteger('warna_id');
            
            $table->timestamps();

            $table->foreign('transaksi_id')->references('transaksi_id')->on('t_transaksi');
            $table->foreign('pembayaran_id')->references('pembayaran_id')->on('t_pembayaran');
            $table->foreign('produk_id')->references('produk_id')->on('t_produk');
            $table->foreign('ukuran_id')->references('ukuran_id')->on('m_ukuran');
            $table->foreign('warna_id')->references('warna_id')->on('m_warna');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_detail_transaksi');
    }
};
