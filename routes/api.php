<?php

use App\Http\Controllers\Api\mobile\BalanceController;
use App\Http\Controllers\Api\mobile\CashBalanceController;
use App\Http\Controllers\Api\mobile\UserController;
use App\Http\Controllers\Api\partner\DealPartnerController;
use App\Http\Controllers\Api\partner\DealProductChangeController;
use App\Http\Controllers\Api\partner\ItemsPartnerController;
use App\Http\Controllers\Api\partner\OrderDetailsPartnerController;
use App\Http\Controllers\Api\partner\OrderPartnerController;
use App\Http\Controllers\Api\partner\PartnerRolePartnerController;
use App\Http\Controllers\Api\partner\PlanLabelPartnerController;
use App\Http\Controllers\Api\partner\PlatformPartnerController;
use App\Http\Controllers\Api\partner\SalesDashboardController;
use App\Http\Controllers\Api\partner\UserPartnerController;
use App\Http\Controllers\Api\payment\OrderSimulationController;
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

        Route::get('/action/historys', [App\Http\Controllers\SharesController::class, 'index'])->name('api_action_history');
        Route::get('/user-balances/{idAmounts}', [App\Http\Controllers\UsersBalancesController::class, 'index'])->name('api_user_balances');
        Route::get('/user-balances-list/{idUser}/{idAmounts}', [App\Http\Controllers\UsersBalancesController::class, 'list'])->name('api_user_balances_list');
        Route::get('/shares-solde-list/{idUser}', [App\Http\Controllers\SharesController::class, 'list'])->name('api_shares_solde_list');
        Route::get('/history/notification', [App\Http\Controllers\NotificationsController::class, 'index'])->name('api_history_notification');
        Route::get('/coupons', [App\Http\Controllers\CouponsController::class, 'index'])->name('api_coupon');
        Route::get('/coupons/injector', [App\Http\Controllers\VoucherController::class, 'index'])->name('api_coupon_injector');
        Route::get('/user/coupons', [App\Http\Controllers\VoucherController::class, 'user'])->name('api_user_coupon_injector');
        Route::get('/coupons/user', [App\Http\Controllers\VoucherController::class, 'userCoupons'])->name('api_user_coupon');
        Route::get('/platforms', [App\Http\Controllers\PlatformController::class, 'index'])->name('api_platforms');
        Route::get('/roles', [App\Http\Controllers\RolesController::class, 'index'])->name('api_role');
        Route::get('/deals/search', [\App\Livewire\DealsIndex::class, 'filterDeals'])->name('api_deal_search');
        Route::get('/request', [App\Http\Controllers\RequestController::class, 'index'])->name('api_request');
        Route::get('/representatives', [App\Http\Controllers\RepresentativesController::class, 'index'])->name('api_representatives');
        Route::get('/user/invitations', [App\Http\Controllers\UserssController::class, 'invitations'])->name('api_user_invitations');
        Route::get('/user/purchaseBFS/{type}', [App\Http\Controllers\UserssController::class, 'getPurchaseBFSUser'])->name('api_user_bfs_purchase');
        Route::get('/user/tree', [App\Http\Controllers\UserssController::class, 'getTreeUser'])->name('api_user_tree');
        Route::get('/user/sms', [App\Http\Controllers\UserssController::class, 'getSmsUser'])->name('api_user_sms');
        Route::get('/user/chance', [App\Http\Controllers\UserssController::class, 'getChanceUser'])->name('api_user_chance');
        Route::get('/target/{idTarget}/data', [\App\Http\Controllers\TargetController::class, 'getTargetData'])->name('api_target_data');

        Route::get('/api/shares/solde', [App\Http\Controllers\SharesController::class, 'getSharesSolde'])->name('api_shares_solde');
        Route::get('/api/shares/soldes', [App\Http\Controllers\SharesController::class, 'getSharesSoldes'])->name('api_shares_soldes');
        Route::get('/api/shares/evolution', [App\Http\Controllers\SharesController::class, 'getSharePriceEvolution'])->name('api_share_evolution');


        Route::get('/api/share/evolution/date', [App\Http\Controllers\SharesController::class, 'getSharePriceEvolutionDate'])->name('api_share_evolution_date');
        Route::get('/api/share/evolution/week', [App\Http\Controllers\SharesController::class, 'getSharePriceEvolutionWeek'])->name('api_share_evolution_week');
        Route::get('/api/share/evolution/month', [App\Http\Controllers\SharesController::class, 'getSharePriceEvolutionMonth'])->name('api_share_evolution_month');
        Route::get('/api/share/evolution/day', [App\Http\Controllers\SharesController::class, 'getSharePriceEvolutionDay'])->name('api_share_evolution_day');
        Route::get('/api/share/evolution/user', [App\Http\Controllers\SharesController::class, 'getSharePriceEvolutionUser'])->name('api_share_evolution_user');

        Route::get('/api/transfert', [App\Http\Controllers\BalancesController::class, 'getTransfert'])->name('api_transfert');
        Route::get('/api/user/cash', [App\Http\Controllers\UsersBalancesController::class, 'getUserCashBalanceQuery'])->name('api_user_cash');


        Route::get('/api/action/values', [App\Http\Controllers\SharesController::class, 'getActionValues'])->name('api_action_values');
        Route::post('/api/coupon/delete', [App\Http\Controllers\CouponsController::class, 'deleteCoupon'])->name('api_delete_coupons');
        Route::post('/api/coupon/injector/delete', [App\Http\Controllers\VoucherController::class, 'deleteInjectorCoupon'])->name('api_delete_injector_coupons');

        Route::get('/get-updated-card-content', [App\Http\Controllers\UsersBalancesController::class, 'getUpdatedCardContent'])->name('get-updated-card-content');

        Route::post('/add-cash', [App\Http\Controllers\BalancesController::class, 'addCash'])->name('add_cash');

        Route::post('/vip', [App\Http\Controllers\VipController::class, 'create'])->name('vip');

        Route::post('/send-sms', [App\Http\Controllers\SmsController::class, 'sendSMS'])->name('send_sms');

        Route::post('/update-balance-status', [App\Http\Controllers\UsersBalancesController::class, 'updateBalanceStatus'])->name('update-balance-status');
        Route::post('/update-reserve-date', [App\Http\Controllers\UsersBalancesController::class, 'updateReserveDate'])->name('update-reserve-date');
        Route::post('/update-balance-real', [App\Http\Controllers\UsersBalancesController::class, 'updateBalanceReal'])->name('update-balance-real');

        Route::get('sankey', 'App\Http\Controllers\ApiController@getSankey')->name('API_sankey');
        Route::post('/paytabs/notification', 'App\Http\Controllers\ApiController@handlePaymentNotification')->name('notification_from_paytabs')->withoutMiddleware('web');


    });
});

// Order Simulation Endpoints
Route::prefix('/order')->name('api_ext_order_')
    ->withoutMiddleware([\App\Http\Middleware\Authenticate::class])
    ->middleware('check.url')
    ->group(function () {
        Route::post('/process', [OrderSimulationController::class, 'processOrder'])->name('process');
        Route::post('/simulate', [OrderSimulationController::class, 'simulateOrder'])->name('simulate');
        Route::post('/run-simulation', [OrderSimulationController::class, 'runSimulation'])->name('run_simulation');
    });

Route::prefix('/v2/')->name('api_v2_')
    ->withoutMiddleware([\App\Http\Middleware\Authenticate::class])
    ->group(function () {
        Route::prefix('balance/operations')->name('balance_operations_')->group(function () {
            Route::get('/filtered', [App\Http\Controllers\BalancesOperationsController::class, 'getFilteredOperations'])->name('filtered');
            Route::get('/all', [App\Http\Controllers\BalancesOperationsController::class, 'getAllOperations'])->name('all');
            Route::get('/categories', [App\Http\Controllers\BalancesOperationsController::class, 'getCategories'])->name('categories');
            Route::get('/category/{categoryId}/name', [App\Http\Controllers\BalancesOperationsController::class, 'getCategoryName'])->name('category_name');
            Route::get('/{id}', [App\Http\Controllers\BalancesOperationsController::class, 'show'])->name('show');
            Route::post('/', [App\Http\Controllers\BalancesOperationsController::class, 'store'])->name('store');
            Route::put('/{id}', [App\Http\Controllers\BalancesOperationsController::class, 'update'])->name('update');
            Route::delete('/{id}', [App\Http\Controllers\BalancesOperationsController::class, 'destroy'])->name('destroy');
            Route::get('/', [App\Http\Controllers\BalancesOperationsController::class, 'index'])->name('index');
        });
    });


Route::prefix('/partner/')->name('api_partner_')
    ->withoutMiddleware([\App\Http\Middleware\Authenticate::class])
    ->group(function () {
        Route::middleware(['check.url'])->group(function () {

            Route::prefix('platforms')->name('platform_')->group(function () {
                Route::get('/top-selling', [PlatformPartnerController::class, 'getTopSellingPlatforms'])->name('top_selling');
                Route::get('/platforms/{platformId}/roles', [PlatformPartnerController::class, 'getRoles'])->name('roles');
                Route::post('/change', [PlatformPartnerController::class, 'changePlatformType'])->name('change_type');
                Route::post('/validate', [PlatformPartnerController::class, 'validateRequest'])->name('validate_request');
                Route::post('/validation/cancel', [PlatformPartnerController::class, 'cancelValidationRequest'])->name('validation_cancel');
                Route::post('/change/cancel', [PlatformPartnerController::class, 'cancelChangeRequest'])->name('change_cancel');
                Route::apiResource('/platforms', PlatformPartnerController::class)->except('destroy');
            });

            Route::prefix('deals')->name('deals_')->group(function () {

                Route::apiResource('/deals', DealPartnerController::class)->except('destroy');
                Route::patch('/{deal}/status', [DealPartnerController::class, 'changeStatus'])->name('change_status');
                Route::post('/validate', [DealPartnerController::class, 'validateRequest'])->name('validate_request');
                Route::post('/validation/cancel', [DealPartnerController::class, 'cancelValidationRequest'])->name('validation_cancel');
                Route::post('/change/cancel', [DealPartnerController::class, 'cancelChangeRequest'])->name('change_cancel');
                Route::get('/dashboard/indicators', [DealPartnerController::class, 'dashboardIndicators'])->name('dashboard_indicators');
                Route::get('/performance/chart', [DealPartnerController::class, 'performanceChart'])->name('performance_chart');

                Route::prefix('product-changes')->name('product_changes_')->group(function () {
                    Route::get('/', [DealProductChangeController::class, 'index'])->name('index');
                    Route::get('/statistics', [DealProductChangeController::class, 'statistics'])->name('statistics');
                    Route::get('/{id}', [DealProductChangeController::class, 'show'])->name('show');
                });
            });

            Route::prefix('orders')->name('orders_')->group(function () {
                Route::apiResource('/orders', OrderPartnerController::class)->except('destroy');
                Route::patch('/{order}/status', [OrderPartnerController::class, 'changeStatus'])->name('change_status');
                Route::apiResource('/details', OrderDetailsPartnerController::class)->only(['store', 'update']);
            });

            Route::prefix('items')->name('items_')->group(function () {
                Route::get('/', [ItemsPartnerController::class, 'listItems'])->name('list');
                Route::post('/', [ItemsPartnerController::class, 'store'])->name('store');
                Route::get('/{id}', [ItemsPartnerController::class, 'show'])->name('show');
                Route::put('/{id}', [ItemsPartnerController::class, 'update'])->name('update');
                Route::delete('/{id}/platform', [ItemsPartnerController::class, 'removePlatformFromItem'])->name('remove_platform');
                Route::get('/deal/{dealId}', [ItemsPartnerController::class, 'listItemsForDeal'])->name('list_by_deal');
                Route::post('/deal/add-bulk', [ItemsPartnerController::class, 'addItemsToDeal'])->name('add_to_deal_bulk');
                Route::post('/deal/remove-bulk', [ItemsPartnerController::class, 'removeItemsFromDeal'])->name('remove_from_deal_bulk');
            });

            Route::prefix('sales/dashboard')->name('sales_dashboard_')->group(function () {
                Route::get('/kpis', [SalesDashboardController::class, 'getKpis'])->name('kpis');
                Route::get('/evolution-chart', [SalesDashboardController::class, 'getSalesEvolutionChart'])->name('chart');
                Route::get('/top-products', [SalesDashboardController::class, 'getTopSellingProducts'])->name('top_products');
                Route::get('/top-deals', [SalesDashboardController::class, 'getTopSellingDeals'])->name('top_deals');
                Route::get('/transactions', [SalesDashboardController::class, 'getTransactions'])->name('transactions');
                Route::get('/transactions/details', [SalesDashboardController::class, 'getTransactionsDetails'])->name('transactions_details');
            });

            Route::prefix('payments')->name('payments_')->group(function () {
                Route::get('/', [\App\Http\Controllers\Api\partner\PartnerPaymentController::class, 'index'])->name('index');
                Route::get('/{id}', [\App\Http\Controllers\Api\partner\PartnerPaymentController::class, 'show'])->name('show');
                Route::post('/demand', [\App\Http\Controllers\Api\partner\PartnerPaymentController::class, 'createDemand'])->name('create_demand');
                Route::get('/statistics/summary', [\App\Http\Controllers\Api\partner\PartnerPaymentController::class, 'statistics'])->name('statistics');
            });

            Route::prefix('partner-requests')->name('partner_requests_')->group(function () {
                Route::get('/', [\App\Http\Controllers\Api\Admin\PartnerRequestController::class, 'index'])->name('index');
                Route::get('/{id}', [\App\Http\Controllers\Api\Admin\PartnerRequestController::class, 'show'])->name('show');
                Route::post('/', [\App\Http\Controllers\Api\Admin\PartnerRequestController::class, 'store'])->name('store');
                Route::put('/{id}', [\App\Http\Controllers\Api\Admin\PartnerRequestController::class, 'update'])->name('update');
            });

            Route::prefix('role-requests')->name('role_requests_')->group(function () {
                Route::get('/', [PartnerRolePartnerController::class, 'index'])->name('index');
                Route::get('/{id}', [PartnerRolePartnerController::class, 'show'])->name('show');
                Route::post('/', [PartnerRolePartnerController::class, 'store'])->name('store');
                Route::post('/{id}/cancel', [PartnerRolePartnerController::class, 'cancel'])->name('cancel');
            });

            Route::get('/plan-label', [PlanLabelPartnerController::class, 'index'])->name('deals_plan_label_index');

            Route::prefix('users')->name('users_')->group(function () {
                Route::prefix('platforms')->name('platforms_')->group(function () {
                    Route::post('/add-role', [UserPartnerController::class, 'addRole'])->name('add_role');
                    Route::post('/update-role', [UserPartnerController::class, 'updateRole'])->name('update_role');
                    Route::post('/delete-role', [UserPartnerController::class, 'deleteRole'])->name('delete_role');
                    Route::get('/', [UserPartnerController::class, 'getPartnerPlatforms'])->name('platforms');
                });
                Route::get('/discount-balance', [UserPartnerController::class, 'getDiscountBalance'])->name('discount_balance');
                Route::get('/', [UserController::class, 'getUser'])->name('get_user');
            });
        });

    });

Route::prefix('/mobile/')->name('api_mobile_')
    ->withoutMiddleware([\App\Http\Middleware\Authenticate::class])
    ->group(function () {
        Route::middleware(['check.url'])->group(function () {
            Route::get('/balances', [BalanceController::class, 'getBalances'])->name('get_balances');
            Route::post('/cash-balance', [CashBalanceController::class, 'store'])->name('store');
            Route::get('/cash-balance', [CashBalanceController::class, 'getCashBalance'])->name('get_cash_balance');
        });
    });
