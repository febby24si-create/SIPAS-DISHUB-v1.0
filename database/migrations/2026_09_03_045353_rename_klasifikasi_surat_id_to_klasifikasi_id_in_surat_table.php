<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Rename kolom klasifikasi_surat_id -> klasifikasi_id di tabel surat.
     * Migration asli sudah diubah setelah dijalankan, sehingga DB masih memakai nama lama.
     */
    public function up(): void
    {
        Schema::table('surat', function (Blueprint $table) {
            $table->renameColumn('klasifikasi_surat_id', 'klasifikasi_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surat', function (Blueprint $table) {
            $table->renameColumn('klasifikasi_id', 'klasifikasi_surat_id');
        });
    }
};
