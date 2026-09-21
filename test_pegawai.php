<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$p = App\Models\Pegawai::first();
echo "Pegawai First: " . ($p ? $p->nama : "None") . "\n";
