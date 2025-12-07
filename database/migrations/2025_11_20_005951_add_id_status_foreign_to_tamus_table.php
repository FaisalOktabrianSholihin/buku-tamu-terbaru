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
        Schema::table('tamus', function (Blueprint $table) {
            $table->foreign('id_status')->references('id')->on('statuses')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tamus', function (Blueprint $table) {
            $table->dropForeign(['id_status']);
        });
    }
};
