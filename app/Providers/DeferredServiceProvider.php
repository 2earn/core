<?php

namespace App\Providers;

use App\Services\Balances\Balances;
use App\Services\Communication\Communication;
use App\Services\Sponsorship\Sponsorship;
use App\Services\Targeting\Targeting;
use App\Services\Users\UserToken;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class DeferredServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        // Use singleton for services that maintain state and are used multiple times
        // Laravel's container will automatically resolve constructor dependencies

        $this->app->singleton('Sponsorship', Sponsorship::class);
        $this->app->singleton(Sponsorship::class);

        $this->app->singleton('Targeting', Targeting::class);
        $this->app->singleton(Targeting::class);

        $this->app->singleton('Communication', Communication::class);
        $this->app->singleton(Communication::class);

        $this->app->singleton('Balances', Balances::class);
        $this->app->singleton(Balances::class);

        $this->app->singleton('UserToken', UserToken::class);
        $this->app->singleton(UserToken::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides(): array
    {
        return [
            'Sponsorship',
            Sponsorship::class,
            'Targeting',
            Targeting::class,
            'Communication',
            Communication::class,
            'Balances',
            Balances::class,
            'UserToken',
            UserToken::class,
        ];
    }
}

