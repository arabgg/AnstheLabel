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
            $table->uuid('transaksi_id')->primary();

            $table->uuid('pembayaran_id');
            $table->string('kode_invoice', 50)->unique()->nullable();
            $table->string('nama_customer');
            $table->string('no_telp');
            $table->string('email')->nullable();
            $table->string('alamat')->nullable();
            $table->enum('status_transaksi', ['menunggu pembayaran', 'dikemas', 'dikirim', 'selesai', 'batal'])->default('menunggu pembayaran');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('pembayaran_id')->references('pembayaran_id')->on('t_pembayaran');
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
