<?php

namespace App\Providers;

use App\Kit;
use App\Observers\KitsObserver;
use App\Observers\PecasObserver;
use App\Observers\ServicosObserver;
use App\Peca;
use App\Servico;
use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Carbon::setLocale($this->app->getLocale());
        Servico::observe(ServicosObserver::class);
        Peca::observe(PecasObserver::class);
        Kit::observe(KitsObserver::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
