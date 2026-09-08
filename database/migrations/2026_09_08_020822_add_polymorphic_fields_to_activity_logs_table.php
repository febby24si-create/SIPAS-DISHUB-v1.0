<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->nullableMorphs('subject');
            $table->string('action')->nullable();
            $table->text('description')->nullable();
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            
            // Mengubah aktivitas menjadi nullable agar backward compatible namun tidak memblokir record baru
            $table->string('aktivitas')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->dropMorphs('subject');
            $table->dropColumn(['action', 'description', 'old_values', 'new_values']);
            $table->string('aktivitas')->nullable(false)->change();
        });
    }
};
