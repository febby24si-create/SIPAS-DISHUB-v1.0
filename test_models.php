<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo App\Models\KenaikanPangkat::count() . " KP\n";
echo App\Models\GajiBerkala::count() . " KGB\n";
echo App\Models\PengajuanCuti::count() . " CUTI\n";
