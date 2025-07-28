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
        Schema::create('t_show_produk', function (Blueprint $table) {
            $table->id('show_produk_id');

            //Menghubungkan ke tabel lain
            $table->unsignedBigInteger('produk_id')->index();
            $table->unsignedBigInteger('detail_produk_id')->index();
            $table->unsignedBigInteger('warna_produk_id')->index();

            $table->string('foto_produk');
            $table->boolean('status_foto')->default(false);

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('produk_id')->references('produk_id')->on('t_produk');
            $table->foreign('detail_produk_id')->references('detail_produk_id')->on('t_detail_produk');
            $table->foreign('warna_produk_id')->references('warna_produk_id')->on('m_warna_produk');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_foto_produk');
    }
};
