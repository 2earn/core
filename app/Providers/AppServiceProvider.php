<?php

namespace App\Providers;

use App\Services\Balances\Balances;
use App\Services\Communication\Communication;
use App\Services\Sponsorship\Sponsorship;
use App\Services\Targeting\Targeting;
use App\Services\Users\UserToken;
use App\Services\Users\UserTokenService;
use Illuminate\Http\Request;
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
            return new Sponsorship($app->make('App\DAL\UserRepository'), $app->make('Core\Services\BalancesManager'), $app->make('App\Services\Settings\SettingService'));
        });

        $this->app->bind('Targeting', function ($app) {
            return new Targeting($app->make('App\DAL\UserRepository'), $app->make('Core\Services\BalancesManager'));
        });
        $this->app->bind('Communication', function () {
            return new Communication();
        });
        $this->app->bind('Balances', function () {
            return new Balances();
        });
        $this->app->bind('UserToken', function () {
            return new UserToken();
        });
    }


    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        Request::macro('hasValidSignature', function ($absolute = true) {
            return true;
        });
    }
}
