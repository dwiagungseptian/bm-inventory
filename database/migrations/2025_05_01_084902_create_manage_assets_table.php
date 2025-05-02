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
        Schema::create('manage_assets', function (Blueprint $table) {
            $table->id();
            $table->string('nama_aset');
            $table->string('kode_aset');
            $table->string('keterangan');
            $table->enum('status', ['Tersedia', 'Rusak', 'Dalam Perbaikan', 'Dipakai']);
            $table->string('gambar');
            $table->date('tanggal_pembelian');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manage_assets');
    }
};
