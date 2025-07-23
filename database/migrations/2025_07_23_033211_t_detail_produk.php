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
        Schema::create('t_detail_produk', function (Blueprint $table) {
            $table->id('detail_produk_id');

            //Menghubungkan ke tabel lain
            $table->unsignedBigInteger('produk_id')->index();
            $table->unsignedBigInteger('warna_produk_id')->index();
            $table->unsignedBigInteger('bahan_produk_id')->index();
            $table->unsignedBigInteger('ukuran_produk_id')->index();
            $table->unsignedBigInteger('foto_produk_id')->index();

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('produk_id')->references('produk_id')->on('t_produk');
            $table->foreign('warna_produk_id')->references('warna_produk_id')->on('m_warna_produk');
            $table->foreign('bahan_produk_id')->references('bahan_produk_id')->on('m_bahan_produk');
            $table->foreign('ukuran_produk_id')->references('ukuran_produk_id')->on('m_ukuran_produk');
            $table->foreign('foto_produk_id')->references('foto_produk_id')->on('t_foto_produk');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_detail_produk');
    }
};
