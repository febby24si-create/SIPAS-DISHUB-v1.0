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
        Schema::table('unit_kerja', function (Blueprint $table) {
            $table->foreignId('parent_id')->nullable()->constrained('unit_kerja')->nullOnDelete()->after('kepala_id');
        });

        Schema::table('surat', function (Blueprint $table) {
            $table->foreignId('unit_kerja_id')->nullable()->constrained('unit_kerja')->nullOnDelete()->after('tujuan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surat', function (Blueprint $table) {
            $table->dropForeign(['unit_kerja_id']);
            $table->dropColumn('unit_kerja_id');
        });

        Schema::table('unit_kerja', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn('parent_id');
        });
    }
};
