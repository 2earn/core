<?php

namespace App\Providers;

use App\Services\Sponsorship\Sponsorship;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
        public function register(): void
    {
        $this->app->bind('Sponsorship', function ($app) {
            return new Sponsorship($app->make('App\DAL\UserRepository'),$app->make('Core\Services\BalancesManager'));
        });
    }


    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

       // user_balance::observe(UserBalanceObserver::class);
        Schema::defaultStringLength(191);

    }
}
