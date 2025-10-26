<?php

namespace App\Providers;

use App\Models\Setting;
use App\Models\Laporan_kasir;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        view()->composer('layouts.master', function ($view) {
            $view->with('setting', Setting::where('cabang_id',auth()->user()->cabang_id)->first());
        });
        view()->composer('layouts.sidebar', function ($view) {
            $view->with('kasir', Laporan_kasir::where('cabang_id',auth()->user()->cabang_id)->latest()->first());
        });
        view()->composer('layouts.header', function ($view) {
            $view->with('kasir', Laporan_kasir::where('cabang_id',auth()->user()->cabang_id)->latest()->first());
        });
        view()->composer('layouts.auth', function ($view) {
            $view->with('setting', Setting::first());
        });
        view()->composer('auth.login', function ($view) {
            $view->with('setting', Setting::first());
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        
    }
}
