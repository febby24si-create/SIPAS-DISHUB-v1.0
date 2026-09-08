<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Pegawai;
use App\Models\ActivityLog;
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
        // Entitas lain seperti PengajuanCuti di Phase berikutnya akan diregister ke ActivityLogObserver di sini
    }
}
