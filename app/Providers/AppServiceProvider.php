<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Pegawai;
use App\Models\PengajuanCuti;
use App\Models\KenaikanPangkat;
use App\Models\GajiBerkala;
use App\Observers\PegawaiObserver;
use App\Observers\ActivityLogObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Pegawai::observe(PegawaiObserver::class);
        PengajuanCuti::observe(ActivityLogObserver::class);
        KenaikanPangkat::observe(ActivityLogObserver::class);
        GajiBerkala::observe(ActivityLogObserver::class);
    }
}
