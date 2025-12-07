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
        Schema::table('scan_logs', function (Blueprint $table) {
            $table->foreign('id_tamu')->references('id')->on('tamus')->onDelete('cascade');
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('scan_logs', function (Blueprint $table) {
            $table->dropForeign(['id_tamu']);
            $table->dropForeign(['id_user']);
        });
    }
};
