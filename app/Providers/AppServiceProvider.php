<?php

namespace App\Providers;

use App\Helpers\Sponsorship\Sponsorship;
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
        if ($this->app->environment('local')) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
        $this->app->bind('sponsorship',function(){
            return new Sponsorship();
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
