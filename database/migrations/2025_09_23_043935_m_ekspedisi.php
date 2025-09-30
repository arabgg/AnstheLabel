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
        Schema::create('m_ekspedisi', function (Blueprint $table) {
            $table->id('ekspedisi_id');
            $table->string('nama_ekspedisi');
            $table->boolean('status_ekspedisi')->default(true);
            $table->string('icon');

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('m_ekspedisi');
    }
};
