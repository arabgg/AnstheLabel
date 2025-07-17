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
            $table->unsignedBigInteger('kategori_id')->index();
            $table->unsignedBigInteger('detail_produk_id')->index();

            $table->string('nama_produk', 200);
            $table->string('harga', 100);
            $table->string('foto_produk');
            $table->text('deskripsi');

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('kategori_id')->references('kategori_id')->on('m_kategori');
            $table->foreign('detail_produk_id')->references('detail_produk_id')->on('m_detail_produk');
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
