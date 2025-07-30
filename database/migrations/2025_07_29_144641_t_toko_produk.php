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
        Schema::create('t_toko_produk', function (Blueprint $table) {
            $table->id('toko_produk_id');

            //Menghubungkan ke tabel lain
            $table->unsignedBigInteger('produk_id')->index();
            $table->unsignedBigInteger('toko_id')->index();

            $table->string('url_toko');

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('produk_id')->references('produk_id')->on('t_produk');
            $table->foreign('toko_id')->references('toko_id')->on('m_toko');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
