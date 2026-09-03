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
        Schema::create('surat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jenis_surat_id')->constrained('jenis_surat_id');
            $table->foreignId('klasifikasi_surat_id')->nullable()->constrained('klasifikasi_surat_id')->nullOnDelete();
            $table->foreignId('template_surat_id')->nullable()->constrained('template_surat_id')->nullOnDelete();

            $table->enum('arah',['masuk','keluar']);  //sbg pembeda, bukan pemisah

            $table->string('nomor_surat')->nullable();
            $table->date('tanggal_surat')->nullable();
            $table->date('tanggal_diterima')->nullable();
            $table->string('perihal');
            $table->string('pengirim')->nullable();
            $table->string('tujuan')->nullable();

            $table->string('file_word')->nullable();
            $table->string('file_pdf')->nullable();
            $table->string('file_dokumen')->nullable(); //upload/scan surat masuk
            
            $table->string('status')->default('draft');
            $table->foreignId('created_by')->constrained('users');

            $table->index(['nomor_surat', 'tanggal_surat', 'perihal']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat');
    }
};
