<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gaji_berkala', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_usulan')->unique()->nullable();
            $table->foreignId('pegawai_id')->constrained('pegawai')->cascadeOnDelete();
            $table->integer('gaji_pokok_lama');
            $table->integer('gaji_pokok_baru');
            $table->date('tmt_sebelumnya');
            $table->date('tmt_berikutnya');
            $table->string('status')->default('draft');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gaji_berkala');
    }
};
