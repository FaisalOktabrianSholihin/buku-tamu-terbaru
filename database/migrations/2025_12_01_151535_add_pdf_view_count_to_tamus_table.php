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
            // Menambahkan kolom integer dengan default 0
            $table->integer('pdf_view_count')->default(0)->after('updated_at');
        });
    }

    public function down(): void
    {
        Schema::table('tamus', function (Blueprint $table) {
            $table->dropColumn('pdf_view_count');
        });
    }
};
