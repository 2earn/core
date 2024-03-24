<?php

namespace App\Providers;

use App\DAL\BalanceOperationRepositoty;
use App\DAL\CountriesRepository;
use App\DAL\HistoryNotificationRepository;
use App\DAL\HobbiesRepository;
use App\DAL\LanguageRepository;
use App\DAL\NotificationRepository;

use App\DAL\SettingsRepository;
use App\DAL\Transaction;
use App\DAL\UserBalancesRepository;
use App\DAL\UserContactNumberRepository;
use App\DAL\UserContactRepository;
use App\DAL\UserRepository;
use Core\Interfaces\IBalanceOperationRepositoty;
use Core\Interfaces\ICountriesRepository;
use Core\Interfaces\IHistoryNotificationRepository;
use Core\Interfaces\IHobbiesRepository;
use Core\Interfaces\ILanguageRepository;
use Core\Interfaces\INotificationRepository;
use Core\Interfaces\INotifyEarn;
use Core\Interfaces\ISettingsRepository;
use Core\Interfaces\ITransaction;
use Core\Interfaces\IUserBalancesRepository;
use Core\Interfaces\IUserContactNumberRepository;
use Core\Interfaces\IUserContactRepository;
use Core\Interfaces\IUserRepository;
use Core\Services\CommandeServiceManager;
use Core\Services\NotifyEarn;
use Core\Services\NotifyHelper;
use Core\Services\settingsManager;
use Core\Services\SmsHelper;
use Core\Services\TransactionManager;
use Core\Services\UserBalancesHelper;
use Illuminate\Support\ServiceProvider;



class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

        $this->app->singleton(ILanguageRepository::class, LanguageRepository::class);
        $this->app->singleton(settingsManager::class, settingsManager::class);
        $this->app->singleton(INotificationRepository::class, NotificationRepository::class);
        $this->app->singleton(IUserRepository::class, UserRepository::class);
        $this->app->singleton(IUserBalancesRepository::class, UserBalancesRepository::class);
        $this->app->singleton(ICountriesRepository::class, CountriesRepository::class);
        $this->app->singleton(ITransaction::class, Transaction::class);
        $this->app->singleton(TransactionManager::class, TransactionManager::class);
        $this->app->singleton(IHobbiesRepository::class, HobbiesRepository::class);
        $this->app->singleton(IHistoryNotificationRepository::class, HistoryNotificationRepository::class);
        $this->app->singleton(IUserContactRepository::class, UserContactRepository::class);
        $this->app->singleton(SmsHelper::class, SmsHelper::class);
        $this->app->singleton(UserBalancesHelper::class, UserBalancesHelper::class);

        $this->app->singleton(CommandeServiceManager::class, CommandeServiceManager::class);
        $this->app->singleton(IBalanceOperationRepositoty::class, BalanceOperationRepositoty::class);
        $this->app->singleton(ISettingsRepository::class, SettingsRepository::class);
        $this->app->singleton(INotifyEarn::class, NotifyEarn::class);
        $this->app->singleton(NotifyHelper::class, NotifyHelper::class);

        $this->app->singleton(IUserContactNumberRepository::class, UserContactNumberRepository::class);



    }
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
