<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tanda_tangans', function (Blueprint $table) {
            // Menambah kolom untuk menyimpan Nama Terang penanda tangan
            $table->string('nama_satpam')->nullable()->after('ttd_satpam');
            $table->string('nama_operator')->nullable()->after('ttd_operator');
        });
    }

    public function down(): void
    {
        Schema::table('tanda_tangans', function (Blueprint $table) {
            $table->dropColumn(['nama_satpam', 'nama_operator']);
        });
    }
};
