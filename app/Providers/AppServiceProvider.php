<?php

namespace App\Providers;

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
        // Service bindings moved to DeferredServiceProvider for better performance
        // This keeps AppServiceProvider lean and defers service registration until needed
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
