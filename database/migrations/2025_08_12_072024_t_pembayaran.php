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
            $table->unsignedBigInteger('metode_id');

            $table->enum('status_pembayaran', ['pending', 'lunas', 'gagal'])->default('pending');
            $table->integer('jumlah_produk');
            $table->string('total_harga');
            $table->timestamps();
            
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
