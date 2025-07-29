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
        Schema::create('t_warna_produk', function (Blueprint $table) {
            $table->id('warna_produk_id');

            //Menghubungkan ke tabel lain
            $table->unsignedBigInteger('warna_id')->index();
            $table->unsignedBigInteger('produk_id')->index();

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('warna_id')->references('warna_id')->on('m_warna');
            $table->foreign('produk_id')->references('produk_id')->on('t_produk');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_warna_produk');
    }
};
