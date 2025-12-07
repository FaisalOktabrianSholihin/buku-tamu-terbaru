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
        Schema::create('tanda_tangans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_tamu');
            $table->string('ttd_tamu')->nullable();
            $table->string('ttd_satpam')->nullable();
            $table->string('ttd_operator')->nullable();
            $table->string('ttd_penerima')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tanda_tangans');
    }
};
