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
        Schema::table('maintenances', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('maintenances', function (Blueprint $table) {
            $table->enum('status', [
                'Diajukan',
                'Disetujui Infra',
                'Disetujui Finance',
                'Disetujui Direktur',
                'Ditolak Infra',
                'Ditolak Finance',
                'Ditolak Direktur',
                'Selesai'
            ])->default('Diajukan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::table('maintenances', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('maintenances', function (Blueprint $table) {
            $table->enum('status', ['Diajukan', 'Approve', 'Ditolak'])->default('Diajukan');
        });
    }
};
