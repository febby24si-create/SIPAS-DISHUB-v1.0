<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\UnitKerja;

echo "--- Verifikasi Unit Kerja ---\n";
$bidangs = UnitKerja::whereNull('parent_id')->with('children')->get();
echo "Jumlah Bidang: " . $bidangs->count() . "\n";

foreach ($bidangs as $bidang) {
    echo "BIDANG: {$bidang->nama} (Kode: {$bidang->kode_unit})\n";
    $seksis = $bidang->children;
    echo "  Jumlah Seksi: " . $seksis->count() . "\n";
    foreach ($seksis as $seksi) {
        echo "  - SEKSI: {$seksi->nama} (Kode: {$seksi->kode_unit}, Parent ID: {$seksi->parent_id})\n";
    }
}

echo "\n--- Total Data ---\n";
echo "Total Unit Kerja: " . UnitKerja::count() . "\n";

