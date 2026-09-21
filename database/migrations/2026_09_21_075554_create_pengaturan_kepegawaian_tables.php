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
        Schema::create('system_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('value');
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::create('kategori_bup', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kategori');
            $table->integer('usia_pensiun');
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        Schema::table('pegawai', function (Blueprint $table) {
            $table->foreignId('kategori_bup_id')->nullable()->constrained('kategori_bup')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pegawai', function (Blueprint $table) {
            $table->dropForeign(['kategori_bup_id']);
            $table->dropColumn('kategori_bup_id');
        });

        Schema::dropIfExists('kategori_bup');
        Schema::dropIfExists('system_settings');
    }
};
