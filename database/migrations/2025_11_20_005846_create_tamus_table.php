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
        Schema::create('tamus', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('jabatan');
            $table->string('instansi');
            $table->string('no_hp');
            $table->string('jumlah_tamu');
            $table->string('penerima_tamu');
            $table->string('nopol_kendaraan');
            $table->string('bidang_usaha');
            // $table->string('status_tamu');
            $table->string('no_seal');
            $table->unsignedBigInteger('id_divisi')->nullable();
            $table->unsignedBigInteger('id_status')->nullable();
            $table->string('keperluan');
            $table->string('qr_code')->nullable();
            $table->unsignedBigInteger('id_visit_status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tamus');
    }
};
