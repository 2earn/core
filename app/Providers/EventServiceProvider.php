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
use App\Observers\OrderObserver;
use App\Observers\ShareObserver;
use App\Observers\SmsObserver;
use App\Observers\TreeObserver;
use App\Observers\UserBalanceObserver;
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

    protected $observers = [
        CashBalances::class => [CashObserver::class],
        BFSsBalances::class => [BfssObserver::class],
        DiscountBalances::class => [DiscountObserver::class],
        TreeBalances::class => [TreeObserver::class],
        SMSBalances::class => [SmsObserver::class],
        SharesBalances::class => [ShareObserver::class],
        ChanceBalances::class => [ChanceObserver::class],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
