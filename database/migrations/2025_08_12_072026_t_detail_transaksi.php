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
            $table->uuid('detail_transaksi_id')->primary();

            $table->uuid('transaksi_id');
            $table->uuid('pembayaran_id');
            $table->unsignedBigInteger('produk_id');
            $table->unsignedBigInteger('ukuran_id');
            $table->unsignedBigInteger('warna_id');
            
            $table->integer('jumlah');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

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
