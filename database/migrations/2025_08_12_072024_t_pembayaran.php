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
            $table->uuid('pembayaran_id')->primary();

            $table->unsignedBigInteger('metode_pembayaran_id');

            $table->enum('status_pembayaran', ['Menunggu Pembayaran', 'Lunas', 'Dibatalkan'])->default('Menunggu Pembayaran');
            $table->integer('jumlah_produk');
            $table->string('total_harga');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            
            $table->foreign('metode_pembayaran_id')->references('metode_pembayaran_id')->on('t_metode_pembayaran');
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
