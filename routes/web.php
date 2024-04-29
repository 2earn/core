<?php

use App\Http\Controllers\PostController;
use App\Http\Livewire\AcceptFinancialRequest;
use App\Http\Livewire\Account;
use App\Http\Livewire\ChangePassword;
use App\Http\Livewire\CheckOptCode;
use App\Http\Livewire\Configuration;
use App\Http\Livewire\ContactNumber;
use App\Http\Livewire\Contacts;

use App\Http\Livewire\Description;
use App\Http\Livewire\EditUserContact;
use App\Http\Livewire\EntretienArbre;
use App\Http\Livewire\EvolutionArbre;
use App\Http\Livewire\FinancialTransaction;

//use App\Http\Livewire\FinancialRequest;
use App\Http\Livewire\ForgotPassword;

use App\Http\Livewire\HistoriqueRecuperation;
use App\Http\Livewire\Hobbies;
use App\Http\Livewire\Home;
use App\Http\Livewire\IdentificationRequest;
use App\Http\Livewire\Login;
use App\Http\Livewire\NotificationHistory;
use App\Http\Livewire\NotificationSettings;

//use App\Http\Livewire\UserBalanceBFS;
use App\Http\Livewire\pay;
use App\Http\Livewire\PaymentController;
use App\Http\Livewire\Registre;
use App\Http\Livewire\RequestPublicUser;
use App\Http\Livewire\StripView;
use App\Http\Livewire\TranslateView;
use App\Http\Livewire\UserBalanceBFS;
use App\Http\Livewire\UserBalanceCB;
use App\Http\Livewire\UserBalanceDB;
use App\Http\Livewire\UserBalanceSMS;
use App\Http\Livewire\UserPurchaseHistory;
use App\Http\Livewire\ValidateAccount;
use App\Services\Sponsorship\SponsorshipFacade;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!


|
*/

Route::get('/pdf', function (){


    $data = array(
        'name' => 'ghazi',
        'detail'=>'sdfsdf',
        'sender' => '2earn.cash'
    );

    $pdf = Pdf::loadView('pdf');

    Mail::send('pwd_email',['data' => "azerty"], function($message) use($pdf)
    {


        $message->to('khalil@2earn.cash')->subject('Invoice');

        $message->attachData($pdf->output(), "invoice.pdf");
    });


});

Route::get('test', \App\Http\Livewire\Test::class)->name('test');
Route::get('changePassword/{idUser}', ChangePassword::class)->name('resetPassword');


//Route::get('/tables', function () {
//    return view('tables-datatables', ['name' => 'Victoria']);
// });
Route::get('tables-datatables', [App\Http\Controllers\HomeController::class, 'index'])->name('index');
Route::get('coming-soon-Move', [App\Http\Controllers\HomeController::class, 'index'])->name('ComingMove');
Route::get('coming-soon-learn', [App\Http\Controllers\HomeController::class, 'index'])->name('ComingLearn');
Route::get('coming-soon-shop', [App\Http\Controllers\HomeController::class, 'index'])->name('ComingShop');
Route::get('widgets', [App\Http\Controllers\HomeController::class, 'index'])->name('widgets');
Route::get('login', Login::class)->name('login')->middleware('setLocalLogin');
Route::get('/offline', function () {
    return view('livewire.offline');
});
Route::get('/privacy', function () {
    return view('livewire.privacy')->extends('layouts.master-without-nav')->section('content');
});

//Route::get('user_balance_bfs', UserBalanceBFS::class)->name('user_balance_bfs');

Route::group(['prefix' => '{locale}', 'where' => ['locale' => '[a-zA-Z]{2}'], 'middleware' => 'setlocale'], function () {
    Route::middleware(['auth', 'CloseAuth'])->group(function () {
        Route::get('/admin/identification_request', identificationRequest::class)->name('identificationRequest');
        Route::get('/translation', TranslateView::class)->name('translate');
        Route::get('configuration', Configuration::class)->name('configuration');
        Route::get('/', Home::class)->name('main');
        Route::get('Home', Home::class)->name('home');
        Route::get('Account', Account::class)->name('account');
        Route::get('Contacts', Contacts::class)->name('contacts');
        Route::get('NotificationHistory', NotificationHistory::class)->name('notification_history');
        Route::get('NotificationSettings', NotificationSettings::class)->name('notification_settings');
        Route::get('user_purchase', UserPurchaseHistory::class)->name('user_purchase');
        Route::get('user_list', \App\Http\Livewire\UsersList::class)->name('user_list');
        Route::get('stat_countrie', \App\Http\Livewire\StatCountrie::class)->name('stat_countrie');
        Route::get('sharessolde', \App\Http\Livewire\SharesSolde::class)->name('sharessolde');
        Route::get('shares_sold', \App\Http\Livewire\SharesSold::class)->name('shares_sold');
        Route::get('edit_admin', \App\Http\Livewire\EditAdmin::class)->name('edit_admin');
        Route::get('countries_management', \App\Http\Livewire\CountriesManagement::class)->name('countries_management');

        Route::get('user_balance_sms', UserBalanceSMS::class)->name('user_balance_sms');
        Route::get('user_balance_cb', UserBalanceCB::class)->name('user_balance_cb');


        Route::get('/paytabs/notification', PaymentController::class)->name('paytabs_notification');
        Route::get('user_balance_db', UserBalanceDB::class)->name('user_balance_db');
        Route::get('user_balance_bfs', UserBalanceBFS::class)->name('user_balance_bfs');
        Route::get('financial_transaction', FinancialTransaction::class)->name('financial_transaction');
        Route::get('ContactNumber', ContactNumber::class)->name('ContactNumber');
        Route::get('editContact', EditUserContact::class)->name('editContact');
        Route::get('/balances/exchange/funding/RequestPulicUser', RequestPublicUser::class)->name('RequesPublicUser');
        Route::get('/balances/exchange/funding/strip', stripView::class)->name('paymentstrip');
        Route::get('paytabs', '\App\Http\Livewire\pay@test')->name('paytabs');

        Route::get('Hobbies', Hobbies::class)->name('hobbies');
        Route::get('RecuperationHistory', HistoriqueRecuperation::class)->name('RecuperationHistory');
        Route::get('Tree/evolution', EvolutionArbre::class)->name('TreeEvolution');
        Route::get('Tree/maintenance', EntretienArbre::class)->name('TreeMaintenance');
        Route::get('description', Description::class)->name('description');
        Route::get('/AcceptRequest', AcceptFinancialRequest::class)->name('AcceptFinancialRequest')->middleware('CloseAuth');

         Route::get('/Sponsorship', function () {
             SponsorshipFacade::testexecuteDelayedSponsorship(999952207);
         });

    });
    Route::get('registre', Registre::class)->name('registre');
    Route::get('forgetpassword', ForgotPassword::class)->name('forgetpassword');
    Route::get('/CheckOptCode/{iduser}/{ccode}/{numTel}', CheckOptCode::class)->name('CheckOptCode');
    Route::get('validate-account', ValidateAccount::class)->name('validateaccount');
});

Route::group(['prefix' => 'API'], function () {
    Route::get('countries', 'App\Http\Controllers\ApiController@getCountries')->name('API_countries');
    Route::get('settings', 'App\Http\Controllers\ApiController@getSettings')->name('API_settings');
    Route::get('balanceOperations', 'App\Http\Controllers\ApiController@getBalanceOperations')->name('API_BalOperations');
    Route::get('amounts', 'App\Http\Controllers\ApiController@getAmounts')->name('API_Amounts');
    Route::get('UrlList/{idUser}/{idamount}', 'App\Http\Controllers\ApiController@getUrlList')->name('UrlList');
    Route::get('actionHistorys', 'App\Http\Controllers\ApiController@getActionHistorys')->name('API_ActionHistory');
    Route::get('UserContacts', 'App\Http\Controllers\ApiController@getUserContacts')->name('API_UserContacts');
    Route::get('user_balances/{idAmounts}', 'App\Http\Controllers\ApiController@getUserBalances')->name('API_UserBalances');
    Route::get('user_balances_list/{idUser}/{idAmounts}', 'App\Http\Controllers\ApiController@getUserBalancesList')->name('API_UserBalances_list');
    Route::get('shares_solde_list/{idUser}', 'App\Http\Controllers\ApiController@getSharesSoldeList')->name('API_SharesSolde_list');
    Route::get('user_admin', 'App\Http\Controllers\ApiController@getUserAdmin')->name('API_UserAdmin');
    Route::get('HistoryNotification', 'App\Http\Controllers\ApiController@getHistoryNotification')->name('API_HistoryNotification');
    Route::get('Request', 'App\Http\Controllers\ApiController@getRequest')->name('API_Request');
    Route::get('Representatives', 'App\Http\Controllers\ApiController@getRepresentatives')->name('API_Representatives');
    Route::get('IdentificationRequest', 'App\Http\Controllers\ApiController@getIdentificationRequest')->name('API_IdentificationRequest');
    Route::get('user_balancesCB', 'App\Http\Controllers\ApiController@getUserBalancesCB')->name('API_userBalancesCB');
    Route::get('user_purchase', 'App\Http\Controllers\ApiController@getPurchaseUser')->name('API_userPurchase');
    Route::get('user_manager', 'App\Http\Controllers\ApiController@getAllUsers')->name('API_usermanager');
    Route::get('user_invitations', 'App\Http\Controllers\ApiController@getInvitationsUser')->name('API_userinvitations');
    Route::get('user_purchaseBFS', 'App\Http\Controllers\ApiController@getPurchaseBFSUser')->name('API_userBFSPurchase');
    Route::post('paytabs_notification', 'App\Http\Controllers\ApiController@handlePaymentNotification')->name('paytabs_notification')->withoutMiddleware('web');
    // Route::post('paytabs_notification1', 'App\Http\Controllers\ApiController@handlePaymentNotification1')->name('paytabs_notification1')->withoutMiddleware('web');

    Route::get('users_list', 'App\Http\Controllers\ApiController@getUsersList')->name('API_UsersList');
    Route::get('stat_countries', 'App\Http\Controllers\ApiController@getCountriStat')->name('API_stat_countries');
    Route::get('sankey', 'App\Http\Controllers\ApiController@getSankey')->name('API_sankey');

    Route::get('shares_solde', 'App\Http\Controllers\ApiController@getSharesSolde')->name('API_sharessolde');
    Route::get('shares_soldes', 'App\Http\Controllers\ApiController@getSharesSoldes')->name('API_sharessoldes');
    Route::get('transfert', 'App\Http\Controllers\ApiController@getTransfert')->name('API_transfert');
    Route::get('usercash', 'App\Http\Controllers\ApiController@getUserCashBalance')->name('API_usercash');
    Route::get('shareevolution', 'App\Http\Controllers\ApiController@getSharePriceEvolution')->name('API_shareevolution');
    Route::get('shareevolutiondate', 'App\Http\Controllers\ApiController@getSharePriceEvolutionDate')->name('API_shareevolutiondate');
    Route::get('shareevolutionweek', 'App\Http\Controllers\ApiController@getSharePriceEvolutionWeek')->name('API_shareevolutionweek');
    Route::get('shareevolutionmonth', 'App\Http\Controllers\ApiController@getSharePriceEvolutionMonth')->name('API_shareevolutionmonth');
    Route::get('shareevolutionday', 'App\Http\Controllers\ApiController@getSharePriceEvolutionDay')->name('API_shareevolutionday');
    Route::get('shareevolutionuser', 'App\Http\Controllers\ApiController@getSharePriceEvolutionUser')->name('API_shareevolutionuser');

    Route::get('actionvalues', 'App\Http\Controllers\ApiController@getActionValues')->name('API_actionvalues');

    Route::get('get-updated-card-content', 'App\Http\Controllers\ApiController@getUpdatedCardContent')->name('get-updated-card-content');
    Route::post('add_cash', 'App\Http\Controllers\ApiController@addCash')->name('addCash');
    Route::post('update-balance-status', 'App\Http\Controllers\ApiController@updateBalanceStatus')->name('update-balance-status');
    Route::post('update-reserve-date', 'App\Http\Controllers\ApiController@updateReserveDate')->name('update-reserve-date');
    Route::post('update-balance-real', 'App\Http\Controllers\ApiController@updateBalanceReal')->name('update-balance-real');
    Route::post('validate-phone', 'App\Http\Controllers\ApiController@validatePhone')->name('validate_phone');

    Route::post('buy-action', 'App\Http\Controllers\ApiController@buyAction')->name('buyAction');
    Route::get('action-by-ammount', 'App\Http\Controllers\ApiController@actionByAmmount')->name('action-by-ammount');
    Route::post('gift-action-by-ammount', 'App\Http\Controllers\ApiController@giftActionByAmmount')->name('gift-action-by-ammount');
});

Route::get('/ResetNot', 'App\Http\Controllers\FinancialRequestController@resetInComingNotification')->name('resetInComingNotification');
Route::get('/ResetNotOut', 'App\Http\Controllers\FinancialRequestController@resetOutGoingNotification')->name('resetOutGoingNotification');
Route::get('/', function () {
    return redirect(app()->getLocale());
});
//Route::get('/login', function () {
//    return redirect( '/'. app()->getLocale().'/login');
//});
Route::get('store-form', 'App\Http\Controllers\PostController@store')->name('saveph');
Route::get('mailVerif', 'App\Http\Controllers\PostController@verifyMail')->name('mailVerif');
Route::get('mailVerifOpt', 'App\Http\Controllers\PostController@mailVerifOpt')->name('mailVerifOpt');
Route::get('mailVerifNew', 'App\Http\Controllers\PostController@mailVerifNew')->name('mailVerifNew');

Route::get('sendMailNotification', 'App\Http\Controllers\PostController@sendMail')->name('sendMail');
Route::get('members', 'App\Http\Controllers\PostController@getMember')->name('members');

Route::get('getRequestAjax', 'App\Http\Controllers\ApiController@getRequestAjax')->name('getRequestAjax');
Route::get('logoutSSo', 'App\Http\Controllers\ApiController@logoutSSo')->name('logoutSSo')->middleware('auth:api');
Route::view('/tests', 'tests');




/// end added by Amine
