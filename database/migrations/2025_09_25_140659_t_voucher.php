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
        Schema::create('t_voucher', function (Blueprint $table) {
            $table->id('voucher_id');
            $table->string('kode_voucher')->unique();
            $table->text('deskripsi')->nullable();

            // tipe diskon: persen atau nominal (tetap)
            $table->enum('tipe_diskon', ['persen', 'nominal']);

            // nilai diskon (contoh: 10 → 10% atau 50000 → Rp 50.000)
            $table->decimal('nilai_diskon', 12, 2);

            // syarat & ketentuan
            $table->decimal('min_transaksi', 12, 2)->default(0); 
            $table->integer('usage_limit')->nullable();
            $table->integer('used')->default(0);

            // periode berlaku
            $table->timestamp('tanggal_mulai')->nullable();
            $table->timestamp('tanggal_berakhir')->nullable();

            $table->boolean('status_voucher')->default(true);

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_voucher');
    }
};
