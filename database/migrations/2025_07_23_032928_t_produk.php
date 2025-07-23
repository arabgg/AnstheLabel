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
        Schema::create('t_produk', function (Blueprint $table) {
            $table->id('produk_id');

            //Menghubungkan ke tabel lain
            $table->unsignedBigInteger('kategori_produk_id')->index();
            $table->unsignedBigInteger('toko_produk_id')->index();

            $table->string('nama_produk', 200);
            $table->string('url_toko');
            $table->text('deskripsi');

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('kategori_produk_id')->references('kategori_produk_id')->on('m_kategori_produk');
            $table->foreign('toko_produk_id')->references('toko_produk_id')->on('m_toko_produk');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_produk');
    }
};
