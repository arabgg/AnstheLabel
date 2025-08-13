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

            $table->string('kode_transaksi', 10)->unique();
            $table->string('nama_customer');
            $table->string('no_telp', 20);
            $table->string('alamat');
            $table->timestamp('tanggal_transaksi')->useCurrent();

            $table->enum('status_transaksi', ['pembayaran berhasil', 'dikemas', 'dikirim', 'diterima'])->default('pembayaran berhasil');
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
