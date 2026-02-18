<?php


use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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

Route::get('/pdf', function () {
    $pdf = Pdf::loadView('pdf');
    Mail::send('pwd_email', ['data' => "azerty"], function ($message) use ($pdf) {
        $message->to('khalil@2earn.cash')->subject('Invoice');
        $message->attachData($pdf->output(), "invoice.pdf");
    });
});

Route::get('/tables-datatables', [App\Http\Controllers\HomeController::class, 'index'])->name('index');
Route::group(['prefix' => '{locale}', 'where' => ['locale' => '[a-zA-Z]{2}'], 'middleware' => 'setlocale'], function () {
    Route::get('/coming-soon-Move', [App\Http\Controllers\HomeController::class, 'index'])->name('coming_move');
    Route::get('/coming-soon-learn', [App\Http\Controllers\HomeController::class, 'index'])->name('coming_learn');
    Route::get('/coming-soon-shop', [App\Http\Controllers\HomeController::class, 'index'])->name('coming_shop');
});

Route::get('/widgets', [App\Http\Controllers\HomeController::class, 'index'])->name('widgets');




Route::group(['prefix' => '{locale}', 'where' => ['locale' => '[a-zA-Z]{2}'], 'middleware' => 'setlocale'], function () {
    Route::middleware(['auth'])->group(function () {



        Route::get('/stat-countries', 'App\\Http\\Controllers\\ApiController@getCountriStat')->name('api_stat_countries');
        Route::post('/validate-phone', 'App\\Http\\Controllers\\ApiController@validatePhone')->name('validate_phone');
        Route::post('/buy-action', 'App\\Http\\Controllers\\ApiController@buyAction')->name('buy_action');
        Route::get('/action-by-ammount', 'App\\Http\\Controllers\\ApiController@actionByAmmount')->name('action_by_ammount');
        Route::post('/gift-action-by-ammount', 'App\\Http\\Controllers\\ApiController@giftActionByAmmount')->name('gift_action_by_ammount');
    });



    Route::get('/users/list', 'App\\Http\\Controllers\\ApiController@getUsersList')->name('api_users_list');





});

Route::get('/reset-not', 'App\\Http\\Controllers\\FinancialRequestController@resetInComingNotification')->name('reset_incoming_notification');
Route::get('/reset-not-out', 'App\\Http\\Controllers\\FinancialRequestController@resetOutGoingNotification')->name('reset_out_going_notification');
Route::get('/', function () {
    return redirect(app()->getLocale());
});

Route::get('/store-form', 'App\\Http\\Controllers\\PostController@store')->name('save_ph');
Route::get('/mail-verif', 'App\\Http\\Controllers\\PostController@verifyMail')->name('mailVerif');
Route::get('/mail-verif-Opt', 'App\\Http\\Controllers\\PostController@mailVerifOpt')->name('mail_verif_opt');
Route::get('/mail-verif-New', 'App\\Http\\Controllers\\PostController@mailVerifNew')->name('mail_verif_New');

Route::get('/send-mail-notification', 'App\\Http\\Controllers\\PostController@sendMail')->name('sendMail');
Route::get('/members', 'App\\Http\\Controllers\\PostController@getMember')->name('members');

Route::get('/get-request-ajax', 'App\\Http\\Controllers\\ApiController@getRequestAjax')->name('get_request_ajax');
Route::get('/logout-sso', 'App\\Http\\Controllers\\ApiController@logoutSSo')->name('logout_sso')->middleware('auth:api');
Route::view('/tests', 'tests');

Route::get('/', function () {
    return redirect(app()->getLocale());
});

Route::get('/{slug}', function () {
    return redirect(app()->getLocale());
});

Route::group(['prefix' => '{locale}', 'where' => ['locale' => '[a-zA-Z]{2}'], 'middleware' => 'setlocale'], function () {
    Route::get('/{slug}', function () {
        throw new NotFoundHttpException();
    });
});

Route::get('/oauth/callback', [\App\Http\Controllers\OAuthController::class, 'callback']);
