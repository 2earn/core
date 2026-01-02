<?php

namespace App\Providers;

use App\Models\BFSsBalances;
use App\Models\CashBalances;
use App\Models\ChanceBalances;
use App\Models\CommissionBreakDown;
use App\Models\DiscountBalances;
use App\Models\SharesBalances;
use App\Models\SMSBalances;
use App\Models\TreeBalances;
use App\Observers\BfssObserver;
use App\Observers\CashObserver;
use App\Observers\ChanceObserver;
use App\Observers\DiscountObserver;
use App\Observers\ShareObserver;
use App\Observers\SmsObserver;
use App\Observers\TreeObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [SendEmailVerificationNotification::class],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        // Register observers only when needed
        CashBalances::observe(CashObserver::class);
        BFSsBalances::observe(BfssObserver::class);
        DiscountBalances::observe(DiscountObserver::class);
        TreeBalances::observe(TreeObserver::class);
        SMSBalances::observe(SmsObserver::class);
        SharesBalances::observe(ShareObserver::class);
        ChanceBalances::observe(ChanceObserver::class);
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents(): bool
    {
        return false; // Disable auto-discovery for better performance
    }
}
