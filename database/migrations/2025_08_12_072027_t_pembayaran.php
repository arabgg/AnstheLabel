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
        Schema::create('t_pembayaran', function (Blueprint $table) {
            $table->id('pembayaran_id');

            // Relasi ke tabel lain
            $table->unsignedBigInteger('transaksi_id');
            $table->unsignedBigInteger('metode_id');
            $table->unsignedBigInteger('produk_id');

            $table->enum('status_pembayaran', ['pending', 'lunas', 'gagal'])->default('pending');
            $table->integer('jumlah');
            $table->string('total_harga');
            $table->timestamp('tanggal_bayar')->nullable();
            $table->timestamps();

            $table->foreign('transaksi_id')->references('transaksi_id')->on('t_transaksi');
            $table->foreign('produk_id')->references('produk_id')->on('t_produk');
            $table->foreign('metode_id')->references('metode_id')->on('m_metode_pembayaran');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_pembayaran');
    }
};
