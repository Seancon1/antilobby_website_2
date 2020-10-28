<?php

namespace App\Providers;

use ConsoleTVs\Charts\Registrar as Charts;
use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Charts $charts, UrlGenerator $url)
    {
        $url->forceScheme('https'); // added this to prevent outside libraries from using http for requests
        Paginator::useBootstrap();
        //
        /*
        $charts->register([
            \App\Charts\SingleSessionChart::class
        ]);
        */
    }
}
