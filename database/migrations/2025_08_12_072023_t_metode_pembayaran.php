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
        Schema::create('t_metode_pembayaran', function (Blueprint $table) {
            $table->id('metode_pembayaran_id');

            //Menghubungkan ke tabel lain
            $table->unsignedBigInteger('metode_id')->index();

            $table->string('nama_pembayaran', 200);
            $table->string('kode_bayar', 50);
            $table->boolean('status_pembayaran')->default(false);
            $table->string('icon')->nullable();

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('metode_id')->references('metode_id')->on('m_metode_pembayaran');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_metode_pembayaran');
    }
};
