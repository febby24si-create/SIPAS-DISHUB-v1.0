<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
use Illuminate\Support\Facades\Schema;

$tables = ['pegawai', 'unit_kerja', 'riwayat_jabatan_pangkat', 'pengajuan_cuti', 'kenaikan_pangkat', 'gaji_berkala', 'users'];
foreach($tables as $t) {
    echo strtoupper($t) . ":\n";
    if(Schema::hasTable($t)){
        foreach(Schema::getColumnListing($t) as $c) {
            echo ' - ' . $c . ' (' . Schema::getColumnType($t, $c) . ")\n";
        }
    } else {
        echo " TABLE NOT FOUND\n";
    }
    echo "\n";
}
