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
            $table->unsignedBigInteger('bahan_id')->index();

            $table->string('nama_produk', 200);
            $table->integer('stok_produk');
            $table->decimal('harga', 10, 2);
            $table->decimal('diskon', 10, 2)->nullable();
            $table->text('deskripsi');
            $table->boolean('is_best')->default(false);

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('kategori_id')->references('kategori_id')->on('m_kategori');
            $table->foreign('bahan_id')->references('bahan_id')->on('m_bahan');
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
