<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::middleware('auth:api')->get('/logmeout', function (Request $request) {
    $user = $request->user();
    $accessToken = $user->token();
    DB::table('oauth_refresh_tokens')
        ->where('access_token_id', $accessToken->id)
        ->delete();
    $user->token()->delete();
    return response()->json([
        'message' => 'Successfully logged out',
        'session' => session()->all()
    ]);
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::group(['prefix' => 'v1'], function () {
        Route::get('/countries', [App\Http\Controllers\CountriesController::class, 'index'])->name('api_countries');
        Route::get('/settings', [App\Http\Controllers\SettingsController::class, 'index'])->name('api_settings');
        Route::get('/amounts', [App\Http\Controllers\SettingsController::class, 'getAmounts'])->name('api_Amounts');
        Route::get('/balance/operations', [App\Http\Controllers\BalancesOperationsController::class, 'index'])->name('api_balance_operations');
        Route::get('/balance/operations/categories', [App\Http\Controllers\BalancesOperationsController::class, 'getCategories'])->name('api_operations_categories');
        Route::get('/action/historys', [App\Http\Controllers\SharesController::class, 'index'])->name('api_action_history');
        Route::get('/user-balances/{idAmounts}', [App\Http\Controllers\UsersBalancesController::class, 'index'])->name('api_user_balances');
        Route::get('/user-balances-list/{idUser}/{idAmounts}',  [App\Http\Controllers\UsersBalancesController::class, 'list'])->name('api_user_balances_list');
        Route::get('/shares-solde-list/{idUser}',  [App\Http\Controllers\SharesController::class, 'list'])->name('api_shares_solde_list');
        Route::get('/history/notification', [App\Http\Controllers\NotificationsController::class, 'index'])->name('api_history_notification');
        Route::get('/coupons', [App\Http\Controllers\CouponsController::class, 'index'])->name('api_coupon');
        Route::get('/coupons/injector', [App\Http\Controllers\VoucherController::class, 'index'])->name('api_coupon_injector');
        Route::get('/user/coupons', [App\Http\Controllers\VoucherController::class, 'user'])->name('api_user_coupon_injector');
        Route::get('/coupons/user', [App\Http\Controllers\VoucherController::class, 'userCoupons'])->name('api_user_coupon');


        Route::get('/platforms', [App\Http\Controllers\PlatformController::class, 'index'])->name('api_platforms');

        Route::get('/roles', 'App\Http\Controllers\ApiController@getRoles')->name('api_role');
        Route::get('/deals/search', [\App\Livewire\DealsIndex::class, 'filterDeals'])->name('api_deal_search');
        Route::get('/request', 'App\Http\Controllers\ApiController@getRequest')->name('api_request');
        Route::get('/representatives', 'App\Http\Controllers\ApiController@getRepresentatives')->name('api_representatives');
        Route::get('/user/balancesCB', 'App\Http\Controllers\ApiController@getUserBalancesCB')->name('api_user_balances_cb');
        Route::get('/user/invitations', 'App\Http\Controllers\ApiController@getInvitationsUser')->name('api_user_invitations');
        Route::get('/user/purchaseBFS/{type}', 'App\Http\Controllers\ApiController@getPurchaseBFSUser')->name('api_user_bfs_purchase');
        Route::get('/user/tree', 'App\Http\Controllers\ApiController@getTreeUser')->name('api_user_tree');
        Route::get('/user/sms', 'App\Http\Controllers\ApiController@getSmsUser')->name('api_user_sms');
        Route::get('/user/chance', 'App\Http\Controllers\ApiController@getChanceUser')->name('api_user_chance');
        Route::post('/paytabs/notification', 'App\Http\Controllers\ApiController@handlePaymentNotification')->name('notification_from_paytabs')->withoutMiddleware('web');
        Route::get('/target/{idTarget}/data', [\App\Http\Controllers\TargetController::class, 'getTargetData'])->name('api_target_data');

        Route::get('sankey', 'App\Http\Controllers\ApiController@getSankey')->name('API_sankey');

        Route::get('/api/shares/solde', 'App\Http\Controllers\ApiController@getSharesSolde')->name('api_shares_solde');
        Route::get('/api/shares/soldes', 'App\Http\Controllers\ApiController@getSharesSoldes')->name('api_shares_soldes');
        Route::get('/api/transfert', 'App\Http\Controllers\ApiController@getTransfert')->name('api_transfert');
        Route::get('/api/user/cash', 'App\Http\Controllers\ApiController@getUserCashBalance')->name('api_user_cash');
        Route::get('/api/share/evolution', 'App\Http\Controllers\ApiController@getSharePriceEvolution')->name('api_share_evolution');
        Route::get('/api/share/evolution/date', 'App\Http\Controllers\ApiController@getSharePriceEvolutionDate')->name('api_share_evolution_date');
        Route::get('/api/share/evolution/week', 'App\Http\Controllers\ApiController@getSharePriceEvolutionWeek')->name('api_share_evolution_week');
        Route::get('/api/share/evolution/month', 'App\Http\Controllers\ApiController@getSharePriceEvolutionMonth')->name('api_share_evolution_month');
        Route::get('/api/share/evolution/day', 'App\Http\Controllers\ApiController@getSharePriceEvolutionDay')->name('api_share_evolution_day');
        Route::get('/api/share/evolution/user', 'App\Http\Controllers\ApiController@getSharePriceEvolutionUser')->name('api_share_evolution_user');

        Route::get('/api/action/values', 'App\Http\Controllers\ApiController@getActionValues')->name('api_action_values');
        Route::post('/api/coupon/delete', 'App\Http\Controllers\ApiController@deleteCoupon')->name('api_delete_coupons');
        Route::post('/api/coupon/injector/delete', 'App\Http\Controllers\ApiController@deleteInjectorCoupon')->name('api_delete_injector_coupons');

        Route::get('/get-updated-card-content', 'App\Http\Controllers\ApiController@getUpdatedCardContent')->name('get-updated-card-content');
        Route::post('/add-cash', 'App\Http\Controllers\ApiController@addCash')->name('add_cash');
        Route::post('/vip', 'App\Http\Controllers\ApiController@vip')->name('vip');
        Route::post('/send-sms', 'App\Http\Controllers\ApiController@sendSMS')->name('send_sms');
        Route::post('/update-balance-status', 'App\Http\Controllers\ApiController@updateBalanceStatus')->name('update-balance-status');
        Route::post('/update-reserve-date', 'App\Http\Controllers\ApiController@updateReserveDate')->name('update-reserve-date');
        Route::post('/update-balance-real', 'App\Http\Controllers\ApiController@updateBalanceReal')->name('update-balance-real');

    });
});

