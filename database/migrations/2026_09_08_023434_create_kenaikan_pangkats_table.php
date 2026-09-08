<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kenaikan_pangkat', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_usulan')->unique()->nullable();
            $table->foreignId('pegawai_id')->constrained('pegawai')->cascadeOnDelete();
            $table->string('pangkat_lama');
            $table->string('golongan_lama');
            $table->string('pangkat_baru');
            $table->string('golongan_baru');
            $table->date('tmt');
            $table->string('status')->default('draft');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kenaikan_pangkat');
    }
};
