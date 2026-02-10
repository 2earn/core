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

        // Balance Operations v1 routes (pointing to v2 controller)
        Route::prefix('balance/operations')->name('balance_operations_')->group(function () {
            Route::get('/filtered', [\App\Http\Controllers\Api\v2\BalancesOperationsController::class, 'getFilteredOperations'])->name('filtered');
            Route::get('/all', [\App\Http\Controllers\Api\v2\BalancesOperationsController::class, 'getAllOperations'])->name('all');
            Route::get('/categories', [\App\Http\Controllers\Api\v2\BalancesOperationsController::class, 'getCategories'])->name('categories');
            Route::get('/category/{categoryId}/name', [\App\Http\Controllers\Api\v2\BalancesOperationsController::class, 'getCategoryName'])->name('category_name');
            Route::get('/{id}', [\App\Http\Controllers\Api\v2\BalancesOperationsController::class, 'show'])->name('show');
            Route::post('/', [\App\Http\Controllers\Api\v2\BalancesOperationsController::class, 'store'])->name('store');
            Route::put('/{id}', [\App\Http\Controllers\Api\v2\BalancesOperationsController::class, 'update'])->name('update');
            Route::delete('/{id}', [\App\Http\Controllers\Api\v2\BalancesOperationsController::class, 'destroy'])->name('destroy');
            Route::get('/', [\App\Http\Controllers\Api\v2\BalancesOperationsController::class, 'index'])->name('index');
        });

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


Route::prefix('/v2/')->name('api_v2_')
    ->withoutMiddleware([\App\Http\Middleware\Authenticate::class])
    ->group(function () {
        Route::prefix('balance/operations')->name('balance_operations_')->group(function () {
            Route::get('/filtered', [\App\Http\Controllers\Api\v2\BalancesOperationsController::class, 'getFilteredOperations'])->name('filtered');
            Route::get('/all', [\App\Http\Controllers\Api\v2\BalancesOperationsController::class, 'getAllOperations'])->name('all');
            Route::get('/categories', [\App\Http\Controllers\Api\v2\BalancesOperationsController::class, 'getCategories'])->name('categories');
            Route::get('/category/{categoryId}/name', [\App\Http\Controllers\Api\v2\BalancesOperationsController::class, 'getCategoryName'])->name('category_name');
            Route::get('/{id}', [\App\Http\Controllers\Api\v2\BalancesOperationsController::class, 'show'])->name('show');
            Route::post('/', [\App\Http\Controllers\Api\v2\BalancesOperationsController::class, 'store'])->name('store');
            Route::put('/{id}', [\App\Http\Controllers\Api\v2\BalancesOperationsController::class, 'update'])->name('update');
            Route::delete('/{id}', [\App\Http\Controllers\Api\v2\BalancesOperationsController::class, 'destroy'])->name('destroy');
            Route::get('/', [\App\Http\Controllers\Api\v2\BalancesOperationsController::class, 'index'])->name('index');
        });

        Route::prefix('business-sectors')->name('business_sectors_')->group(function () {
            Route::get('/', [\App\Http\Controllers\Api\v2\BusinessSectorController::class, 'index'])->name('index');
            Route::get('/all', [\App\Http\Controllers\Api\v2\BusinessSectorController::class, 'all'])->name('all');
            Route::get('/ordered', [\App\Http\Controllers\Api\v2\BusinessSectorController::class, 'ordered'])->name('ordered');
            Route::get('/user-purchases', [\App\Http\Controllers\Api\v2\BusinessSectorController::class, 'userPurchases'])->name('user_purchases');
            Route::get('/{id}', [\App\Http\Controllers\Api\v2\BusinessSectorController::class, 'show'])->name('show');
            Route::get('/{id}/with-images', [\App\Http\Controllers\Api\v2\BusinessSectorController::class, 'showWithImages'])->name('show_with_images');
            Route::post('/', [\App\Http\Controllers\Api\v2\BusinessSectorController::class, 'store'])->name('store');
            Route::put('/{id}', [\App\Http\Controllers\Api\v2\BusinessSectorController::class, 'update'])->name('update');
            Route::delete('/{id}', [\App\Http\Controllers\Api\v2\BusinessSectorController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('comments')->name('comments_')->group(function () {
            Route::get('/validated', [\App\Http\Controllers\Api\v2\CommentsController::class, 'getValidated'])->name('validated');
            Route::get('/unvalidated', [\App\Http\Controllers\Api\v2\CommentsController::class, 'getUnvalidated'])->name('unvalidated');
            Route::get('/all', [\App\Http\Controllers\Api\v2\CommentsController::class, 'getAll'])->name('all');
            Route::get('/count', [\App\Http\Controllers\Api\v2\CommentsController::class, 'getCount'])->name('count');
            Route::get('/has-commented', [\App\Http\Controllers\Api\v2\CommentsController::class, 'hasUserCommented'])->name('has_commented');
            Route::post('/', [\App\Http\Controllers\Api\v2\CommentsController::class, 'store'])->name('store');
            Route::post('/{id}/validate', [\App\Http\Controllers\Api\v2\CommentsController::class, 'validateComment'])->name('validate');
            Route::delete('/{id}', [\App\Http\Controllers\Api\v2\CommentsController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('communication')->name('communication_')->group(function () {
            Route::post('/surveys/{id}/duplicate', [\App\Http\Controllers\Api\v2\CommunicationController::class, 'duplicateSurvey'])->name('duplicate_survey');
            Route::post('/news/{id}/duplicate', [\App\Http\Controllers\Api\v2\CommunicationController::class, 'duplicateNews'])->name('duplicate_news');
            Route::post('/events/{id}/duplicate', [\App\Http\Controllers\Api\v2\CommunicationController::class, 'duplicateEvent'])->name('duplicate_event');
        });

        Route::prefix('coupons')->name('coupons_')->group(function () {
            Route::get('/', [\App\Http\Controllers\Api\v2\CouponController::class, 'index'])->name('index');
            Route::get('/all', [\App\Http\Controllers\Api\v2\CouponController::class, 'all'])->name('all');
            Route::get('/by-sn', [\App\Http\Controllers\Api\v2\CouponController::class, 'getBySn'])->name('by_sn');
            Route::get('/users/{userId}', [\App\Http\Controllers\Api\v2\CouponController::class, 'getUserCoupons'])->name('user_coupons');
            Route::get('/users/{userId}/purchased', [\App\Http\Controllers\Api\v2\CouponController::class, 'getPurchasedCoupons'])->name('purchased_coupons');
            Route::get('/users/{userId}/status/{status}', [\App\Http\Controllers\Api\v2\CouponController::class, 'getPurchasedByStatus'])->name('by_status');
            Route::get('/platforms/{platformId}/available', [\App\Http\Controllers\Api\v2\CouponController::class, 'getAvailableForPlatform'])->name('available_for_platform');
            Route::get('/platforms/{platformId}/max-amount', [\App\Http\Controllers\Api\v2\CouponController::class, 'getMaxAvailableAmount'])->name('max_amount');
            Route::post('/simulate', [\App\Http\Controllers\Api\v2\CouponController::class, 'simulatePurchase'])->name('simulate');
            Route::post('/buy', [\App\Http\Controllers\Api\v2\CouponController::class, 'buyCoupon'])->name('buy');
            Route::post('/{id}/consume', [\App\Http\Controllers\Api\v2\CouponController::class, 'consume'])->name('consume');
            Route::post('/{id}/mark-consumed', [\App\Http\Controllers\Api\v2\CouponController::class, 'markAsConsumed'])->name('mark_consumed');
            Route::delete('/{id}', [\App\Http\Controllers\Api\v2\CouponController::class, 'destroy'])->name('destroy');
            Route::delete('/multiple', [\App\Http\Controllers\Api\v2\CouponController::class, 'deleteMultiple'])->name('delete_multiple');
            Route::get('/{id}', [\App\Http\Controllers\Api\v2\CouponController::class, 'show'])->name('show');
        });

        Route::prefix('balance-injector-coupons')->name('balance_injector_coupons_')->group(function () {
            Route::get('/', [\App\Http\Controllers\Api\v2\BalanceInjectorCouponController::class, 'index'])->name('index');
            Route::get('/all', [\App\Http\Controllers\Api\v2\BalanceInjectorCouponController::class, 'all'])->name('all');
            Route::get('/by-pin', [\App\Http\Controllers\Api\v2\BalanceInjectorCouponController::class, 'getByPin'])->name('by_pin');
            Route::get('/users/{userId}', [\App\Http\Controllers\Api\v2\BalanceInjectorCouponController::class, 'getByUserId'])->name('by_user');
            Route::post('/create-multiple', [\App\Http\Controllers\Api\v2\BalanceInjectorCouponController::class, 'createMultiple'])->name('create_multiple');
            Route::delete('/multiple', [\App\Http\Controllers\Api\v2\BalanceInjectorCouponController::class, 'deleteMultiple'])->name('delete_multiple');
            Route::get('/{id}', [\App\Http\Controllers\Api\v2\BalanceInjectorCouponController::class, 'show'])->name('show');
            Route::delete('/{id}', [\App\Http\Controllers\Api\v2\BalanceInjectorCouponController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('deals')->name('deals_')->group(function () {
            Route::get('/', [\App\Http\Controllers\Api\v2\DealController::class, 'index'])->name('index');
            Route::get('/all', [\App\Http\Controllers\Api\v2\DealController::class, 'all'])->name('all');
            Route::get('/partner', [\App\Http\Controllers\Api\v2\DealController::class, 'getPartnerDeals'])->name('partner');
            Route::get('/available', [\App\Http\Controllers\Api\v2\DealController::class, 'getAvailableDeals'])->name('available');
            Route::get('/archived', [\App\Http\Controllers\Api\v2\DealController::class, 'getArchivedDeals'])->name('archived');
            Route::get('/dashboard-indicators', [\App\Http\Controllers\Api\v2\DealController::class, 'getDashboardIndicators'])->name('dashboard_indicators');
            Route::get('/users/{userId}/purchases', [\App\Http\Controllers\Api\v2\DealController::class, 'getDealsWithPurchases'])->name('user_purchases');
            Route::get('/{id}', [\App\Http\Controllers\Api\v2\DealController::class, 'show'])->name('show');
            Route::get('/{id}/partner', [\App\Http\Controllers\Api\v2\DealController::class, 'getPartnerDeal'])->name('partner_deal');
            Route::get('/{id}/performance-chart', [\App\Http\Controllers\Api\v2\DealController::class, 'getPerformanceChart'])->name('performance_chart');
            Route::get('/{id}/change-requests', [\App\Http\Controllers\Api\v2\DealController::class, 'getChangeRequests'])->name('change_requests');
            Route::get('/{id}/validation-requests', [\App\Http\Controllers\Api\v2\DealController::class, 'getValidationRequests'])->name('validation_requests');
            Route::post('/', [\App\Http\Controllers\Api\v2\DealController::class, 'store'])->name('store');
            Route::put('/{id}', [\App\Http\Controllers\Api\v2\DealController::class, 'update'])->name('update');
            Route::delete('/{id}', [\App\Http\Controllers\Api\v2\DealController::class, 'destroy'])->name('destroy');
            Route::post('/{id}/validation-request', [\App\Http\Controllers\Api\v2\DealController::class, 'createValidationRequest'])->name('create_validation_request');
            Route::post('/{id}/change-request', [\App\Http\Controllers\Api\v2\DealController::class, 'createChangeRequest'])->name('create_change_request');
        });

        Route::prefix('deal-product-changes')->name('deal_product_changes_')->group(function () {
            Route::get('/', [\App\Http\Controllers\Api\v2\DealProductChangeController::class, 'index'])->name('index');
            Route::get('/statistics', [\App\Http\Controllers\Api\v2\DealProductChangeController::class, 'getStatistics'])->name('statistics');
            Route::get('/{id}', [\App\Http\Controllers\Api\v2\DealProductChangeController::class, 'show'])->name('show');
            Route::post('/', [\App\Http\Controllers\Api\v2\DealProductChangeController::class, 'store'])->name('store');
            Route::post('/bulk', [\App\Http\Controllers\Api\v2\DealProductChangeController::class, 'createBulk'])->name('create_bulk');
        });

        Route::prefix('pending-deal-change-requests')->name('pending_deal_change_requests_')->group(function () {
            Route::get('/', [\App\Http\Controllers\Api\v2\PendingDealChangeRequestsController::class, 'index'])->name('index');
            Route::get('/total', [\App\Http\Controllers\Api\v2\PendingDealChangeRequestsController::class, 'getTotalPending'])->name('total');
            Route::get('/with-total', [\App\Http\Controllers\Api\v2\PendingDealChangeRequestsController::class, 'getPendingWithTotal'])->name('with_total');
            Route::get('/{id}', [\App\Http\Controllers\Api\v2\PendingDealChangeRequestsController::class, 'show'])->name('show');
            Route::get('/{id}/with-relations', [\App\Http\Controllers\Api\v2\PendingDealChangeRequestsController::class, 'showWithRelations'])->name('show_with_relations');
        });

        Route::prefix('pending-deal-validation-requests')->name('pending_deal_validation_requests_')->group(function () {
            Route::get('/', [\App\Http\Controllers\Api\v2\PendingDealValidationRequestsController::class, 'index'])->name('index');
            Route::get('/paginated', [\App\Http\Controllers\Api\v2\PendingDealValidationRequestsController::class, 'getPaginated'])->name('paginated');
            Route::get('/total', [\App\Http\Controllers\Api\v2\PendingDealValidationRequestsController::class, 'getTotalPending'])->name('total');
            Route::get('/with-total', [\App\Http\Controllers\Api\v2\PendingDealValidationRequestsController::class, 'getPendingWithTotal'])->name('with_total');
            Route::get('/{id}', [\App\Http\Controllers\Api\v2\PendingDealValidationRequestsController::class, 'show'])->name('show');
            Route::get('/{id}/with-relations', [\App\Http\Controllers\Api\v2\PendingDealValidationRequestsController::class, 'showWithRelations'])->name('show_with_relations');
            Route::post('/{id}/approve', [\App\Http\Controllers\Api\v2\PendingDealValidationRequestsController::class, 'approve'])->name('approve');
            Route::post('/{id}/reject', [\App\Http\Controllers\Api\v2\PendingDealValidationRequestsController::class, 'reject'])->name('reject');
        });

        Route::prefix('items')->name('items_')->group(function () {
            Route::get('/', [\App\Http\Controllers\Api\v2\ItemController::class, 'index'])->name('index');
            Route::get('/by-deal', [\App\Http\Controllers\Api\v2\ItemController::class, 'getByDeal'])->name('by_deal');
            Route::get('/by-ref-platform', [\App\Http\Controllers\Api\v2\ItemController::class, 'findByRefAndPlatform'])->name('by_ref_platform');
            Route::get('/platforms/{platformId}', [\App\Http\Controllers\Api\v2\ItemController::class, 'getByPlatform'])->name('by_platform');
            Route::get('/deals/{dealId}', [\App\Http\Controllers\Api\v2\ItemController::class, 'getForDeal'])->name('for_deal');
            Route::get('/users/{userId}/purchases', [\App\Http\Controllers\Api\v2\ItemController::class, 'getWithUserPurchases'])->name('user_purchases');
            Route::post('/bulk-update-deal', [\App\Http\Controllers\Api\v2\ItemController::class, 'bulkUpdateDeal'])->name('bulk_update_deal');
            Route::post('/bulk-remove-deal', [\App\Http\Controllers\Api\v2\ItemController::class, 'bulkRemoveFromDeal'])->name('bulk_remove_deal');
            Route::get('/{id}', [\App\Http\Controllers\Api\v2\ItemController::class, 'show'])->name('show');
            Route::post('/', [\App\Http\Controllers\Api\v2\ItemController::class, 'store'])->name('store');
            Route::put('/{id}', [\App\Http\Controllers\Api\v2\ItemController::class, 'update'])->name('update');
            Route::delete('/{id}', [\App\Http\Controllers\Api\v2\ItemController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('entity-roles')->name('entity_roles_')->group(function () {
            Route::get('/', [\App\Http\Controllers\Api\v2\EntityRoleController::class, 'index'])->name('index');
            Route::get('/filtered', [\App\Http\Controllers\Api\v2\EntityRoleController::class, 'getFiltered'])->name('filtered');
            Route::get('/platform-roles', [\App\Http\Controllers\Api\v2\EntityRoleController::class, 'getPlatformRoles'])->name('platform_roles');
            Route::get('/partner-roles', [\App\Http\Controllers\Api\v2\EntityRoleController::class, 'getPartnerRoles'])->name('partner_roles');
            Route::get('/search', [\App\Http\Controllers\Api\v2\EntityRoleController::class, 'searchByName'])->name('search');
            Route::get('/platforms/{platformId}', [\App\Http\Controllers\Api\v2\EntityRoleController::class, 'getRolesForPlatform'])->name('for_platform');
            Route::get('/platforms/{platformId}/keyed', [\App\Http\Controllers\Api\v2\EntityRoleController::class, 'getEntityRolesKeyedByName'])->name('keyed_by_name');
            Route::get('/platforms/{platformId}/owner', [\App\Http\Controllers\Api\v2\EntityRoleController::class, 'getPlatformOwnerRole'])->name('platform_owner');
            Route::get('/partners/{partnerId}', [\App\Http\Controllers\Api\v2\EntityRoleController::class, 'getRolesForPartner'])->name('for_partner');
            Route::get('/users/{userId}/platforms', [\App\Http\Controllers\Api\v2\EntityRoleController::class, 'getPlatformsWithRolesForUser'])->name('user_platforms');
            Route::get('/users/{userId}/platform-ids', [\App\Http\Controllers\Api\v2\EntityRoleController::class, 'getUserPlatformIds'])->name('user_platform_ids');
            Route::get('/users/{userId}/partner-ids', [\App\Http\Controllers\Api\v2\EntityRoleController::class, 'getUserPartnerIds'])->name('user_partner_ids');
            Route::get('/users/{userId}/platforms/{platformId}/roles', [\App\Http\Controllers\Api\v2\EntityRoleController::class, 'getUserRolesForPlatform'])->name('user_platform_roles');
            Route::get('/users/{userId}/check-platform-role', [\App\Http\Controllers\Api\v2\EntityRoleController::class, 'checkUserHasPlatformRole'])->name('check_platform_role');
            Route::get('/users/{userId}/check-partner-role', [\App\Http\Controllers\Api\v2\EntityRoleController::class, 'checkUserHasPartnerRole'])->name('check_partner_role');
            Route::post('/platforms/{platformId}', [\App\Http\Controllers\Api\v2\EntityRoleController::class, 'createPlatformRole'])->name('create_platform');
            Route::post('/partners/{partnerId}', [\App\Http\Controllers\Api\v2\EntityRoleController::class, 'createPartnerRole'])->name('create_partner');
            Route::get('/{id}', [\App\Http\Controllers\Api\v2\EntityRoleController::class, 'show'])->name('show');
            Route::put('/{id}', [\App\Http\Controllers\Api\v2\EntityRoleController::class, 'update'])->name('update');
            Route::delete('/{id}', [\App\Http\Controllers\Api\v2\EntityRoleController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('faqs')->name('faqs_')->group(function () {
            Route::get('/', [\App\Http\Controllers\Api\v2\FaqController::class, 'index'])->name('index');
            Route::get('/paginated', [\App\Http\Controllers\Api\v2\FaqController::class, 'getPaginated'])->name('paginated');
            Route::get('/{id}', [\App\Http\Controllers\Api\v2\FaqController::class, 'show'])->name('show');
            Route::post('/', [\App\Http\Controllers\Api\v2\FaqController::class, 'store'])->name('store');
            Route::put('/{id}', [\App\Http\Controllers\Api\v2\FaqController::class, 'update'])->name('update');
            Route::delete('/{id}', [\App\Http\Controllers\Api\v2\FaqController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('hashtags')->name('hashtags_')->group(function () {
            Route::get('/', [\App\Http\Controllers\Api\v2\HashtagController::class, 'index'])->name('index');
            Route::get('/filtered', [\App\Http\Controllers\Api\v2\HashtagController::class, 'getFiltered'])->name('filtered');
            Route::get('/check-exists', [\App\Http\Controllers\Api\v2\HashtagController::class, 'checkExists'])->name('check_exists');
            Route::get('/slug/{slug}', [\App\Http\Controllers\Api\v2\HashtagController::class, 'getBySlug'])->name('by_slug');
            Route::get('/{id}', [\App\Http\Controllers\Api\v2\HashtagController::class, 'show'])->name('show');
            Route::post('/', [\App\Http\Controllers\Api\v2\HashtagController::class, 'store'])->name('store');
            Route::put('/{id}', [\App\Http\Controllers\Api\v2\HashtagController::class, 'update'])->name('update');
            Route::delete('/{id}', [\App\Http\Controllers\Api\v2\HashtagController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('orders')->name('orders_')->group(function () {
            Route::get('/', [\App\Http\Controllers\Api\v2\OrderController::class, 'index'])->name('index');
            Route::get('/dashboard/statistics', [\App\Http\Controllers\Api\v2\OrderController::class, 'getDashboardStatistics'])->name('dashboard_statistics');
            Route::post('/from-cart', [\App\Http\Controllers\Api\v2\OrderController::class, 'createFromCart'])->name('create_from_cart');
            Route::post('/{orderId}/cancel', [\App\Http\Controllers\Api\v2\OrderController::class, 'cancel'])->name('cancel');
            Route::post('/{orderId}/make-ready', [\App\Http\Controllers\Api\v2\OrderController::class, 'makeReady'])->name('make_ready');
            Route::post('/', [\App\Http\Controllers\Api\v2\OrderController::class, 'store'])->name('store');
            Route::post('/users/{userId}/pending-count', [\App\Http\Controllers\Api\v2\OrderController::class, 'getPendingCount'])->name('pending_count');
            Route::post('/users/{userId}/by-ids', [\App\Http\Controllers\Api\v2\OrderController::class, 'getOrdersByIds'])->name('by_ids');
            Route::get('/users/{userId}', [\App\Http\Controllers\Api\v2\OrderController::class, 'getUserOrders'])->name('user_orders');
            Route::get('/users/{userId}/{orderId}', [\App\Http\Controllers\Api\v2\OrderController::class, 'findUserOrder'])->name('user_order');
        });

        Route::prefix('news')->name('news_')->group(function () {
            Route::get('/', [\App\Http\Controllers\Api\v2\NewsController::class, 'index'])->name('index');
            Route::get('/all', [\App\Http\Controllers\Api\v2\NewsController::class, 'all'])->name('all');
            Route::get('/enabled', [\App\Http\Controllers\Api\v2\NewsController::class, 'enabled'])->name('enabled');
            Route::get('/{id}', [\App\Http\Controllers\Api\v2\NewsController::class, 'show'])->name('show');
            Route::get('/{id}/with-relations', [\App\Http\Controllers\Api\v2\NewsController::class, 'showWithRelations'])->name('show_with_relations');
            Route::get('/{id}/has-user-liked', [\App\Http\Controllers\Api\v2\NewsController::class, 'hasUserLiked'])->name('has_user_liked');
            Route::post('/', [\App\Http\Controllers\Api\v2\NewsController::class, 'store'])->name('store');
            Route::post('/{id}/duplicate', [\App\Http\Controllers\Api\v2\NewsController::class, 'duplicate'])->name('duplicate');
            Route::post('/{id}/like', [\App\Http\Controllers\Api\v2\NewsController::class, 'addLike'])->name('add_like');
            Route::delete('/{id}/like', [\App\Http\Controllers\Api\v2\NewsController::class, 'removeLike'])->name('remove_like');
            Route::put('/{id}', [\App\Http\Controllers\Api\v2\NewsController::class, 'update'])->name('update');
            Route::delete('/{id}', [\App\Http\Controllers\Api\v2\NewsController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('roles')->name('roles_')->group(function () {
            Route::get('/', [\App\Http\Controllers\Api\v2\RoleController::class, 'index'])->name('index');
            Route::get('/all', [\App\Http\Controllers\Api\v2\RoleController::class, 'all'])->name('all');
            Route::get('/user-roles', [\App\Http\Controllers\Api\v2\RoleController::class, 'getUserRoles'])->name('user_roles');
            Route::get('/{id}', [\App\Http\Controllers\Api\v2\RoleController::class, 'show'])->name('show');
            Route::get('/{id}/can-delete', [\App\Http\Controllers\Api\v2\RoleController::class, 'canDelete'])->name('can_delete');
            Route::post('/', [\App\Http\Controllers\Api\v2\RoleController::class, 'store'])->name('store');
            Route::put('/{id}', [\App\Http\Controllers\Api\v2\RoleController::class, 'update'])->name('update');
            Route::delete('/{id}', [\App\Http\Controllers\Api\v2\RoleController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('partners')->name('partners_')->group(function () {
            Route::get('/', [\App\Http\Controllers\Api\v2\PartnerController::class, 'index'])->name('index');
            Route::get('/filtered', [\App\Http\Controllers\Api\v2\PartnerController::class, 'filtered'])->name('filtered');
            Route::get('/search', [\App\Http\Controllers\Api\v2\PartnerController::class, 'searchByCompanyName'])->name('search');
            Route::get('/business-sectors/{businessSectorId}', [\App\Http\Controllers\Api\v2\PartnerController::class, 'byBusinessSector'])->name('by_business_sector');
            Route::get('/{id}', [\App\Http\Controllers\Api\v2\PartnerController::class, 'show'])->name('show');
            Route::post('/', [\App\Http\Controllers\Api\v2\PartnerController::class, 'store'])->name('store');
            Route::put('/{id}', [\App\Http\Controllers\Api\v2\PartnerController::class, 'update'])->name('update');
            Route::delete('/{id}', [\App\Http\Controllers\Api\v2\PartnerController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('partner-payments')->name('partner_payments_')->group(function () {
            Route::get('/', [\App\Http\Controllers\Api\v2\PartnerPaymentController::class, 'index'])->name('index');
            Route::get('/pending', [\App\Http\Controllers\Api\v2\PartnerPaymentController::class, 'getPending'])->name('pending');
            Route::get('/validated', [\App\Http\Controllers\Api\v2\PartnerPaymentController::class, 'getValidated'])->name('validated');
            Route::get('/stats', [\App\Http\Controllers\Api\v2\PartnerPaymentController::class, 'getStats'])->name('stats');
            Route::get('/payment-methods', [\App\Http\Controllers\Api\v2\PartnerPaymentController::class, 'getPaymentMethods'])->name('payment_methods');
            Route::get('/partners/{partnerId}', [\App\Http\Controllers\Api\v2\PartnerPaymentController::class, 'getByPartnerId'])->name('by_partner');
            Route::get('/partners/{partnerId}/total', [\App\Http\Controllers\Api\v2\PartnerPaymentController::class, 'getTotalByPartner'])->name('total_by_partner');
            Route::get('/{id}', [\App\Http\Controllers\Api\v2\PartnerPaymentController::class, 'show'])->name('show');
            Route::post('/', [\App\Http\Controllers\Api\v2\PartnerPaymentController::class, 'store'])->name('store');
            Route::post('/{id}/validate', [\App\Http\Controllers\Api\v2\PartnerPaymentController::class, 'validatePayment'])->name('validate');
            Route::post('/{id}/reject', [\App\Http\Controllers\Api\v2\PartnerPaymentController::class, 'rejectPayment'])->name('reject');
            Route::put('/{id}', [\App\Http\Controllers\Api\v2\PartnerPaymentController::class, 'update'])->name('update');
            Route::delete('/{id}', [\App\Http\Controllers\Api\v2\PartnerPaymentController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('pending-deal-validations')->name('pending_deal_validations_')->group(function () {
            Route::get('/', [\App\Http\Controllers\Api\v2\PendingDealValidationRequestsController::class, 'index'])->name('index');
            Route::get('/paginated', [\App\Http\Controllers\Api\v2\PendingDealValidationRequestsController::class, 'getPaginated'])->name('paginated');
            Route::get('/total', [\App\Http\Controllers\Api\v2\PendingDealValidationRequestsController::class, 'getTotalPending'])->name('total');
            Route::get('/with-total', [\App\Http\Controllers\Api\v2\PendingDealValidationRequestsController::class, 'getPendingWithTotal'])->name('with_total');
            Route::get('/{id}', [\App\Http\Controllers\Api\v2\PendingDealValidationRequestsController::class, 'show'])->name('show');
        });

        Route::prefix('pending-platform-changes-inline')->name('pending_platform_changes_inline_')->group(function () {
            Route::get('/', [\App\Http\Controllers\Api\v2\PendingPlatformChangeRequestsInlineController::class, 'index'])->name('index');
            Route::get('/paginated', [\App\Http\Controllers\Api\v2\PendingPlatformChangeRequestsInlineController::class, 'getPaginated'])->name('paginated');
            Route::get('/total', [\App\Http\Controllers\Api\v2\PendingPlatformChangeRequestsInlineController::class, 'getTotalPending'])->name('total');
            Route::get('/{id}', [\App\Http\Controllers\Api\v2\PendingPlatformChangeRequestsInlineController::class, 'show'])->name('show');
        });

        Route::prefix('platforms')->name('platforms_')->group(function () {
            Route::get('/', [\App\Http\Controllers\Api\v2\PlatformController::class, 'index'])->name('index');
            Route::get('/all', [\App\Http\Controllers\Api\v2\PlatformController::class, 'all'])->name('all');
            Route::get('/enabled', [\App\Http\Controllers\Api\v2\PlatformController::class, 'enabled'])->name('enabled');
            Route::get('/with-user-purchases', [\App\Http\Controllers\Api\v2\PlatformController::class, 'withUserPurchases'])->name('with_user_purchases');
            Route::get('/business-sectors/{businessSectorId}/active-deals', [\App\Http\Controllers\Api\v2\PlatformController::class, 'withActiveDeals'])->name('with_active_deals');
            Route::get('/business-sectors/{businessSectorId}/items', [\App\Http\Controllers\Api\v2\PlatformController::class, 'items'])->name('items');
            Route::get('/for-partner', [\App\Http\Controllers\Api\v2\PlatformController::class, 'forPartner'])->name('for_partner');
            Route::get('/with-coupon-deals', [\App\Http\Controllers\Api\v2\PlatformController::class, 'withCouponDeals'])->name('with_coupon_deals');
            Route::get('/coupon-deals-select', [\App\Http\Controllers\Api\v2\PlatformController::class, 'couponDealsSelect'])->name('coupon_deals_select');
            Route::get('/{id}', [\App\Http\Controllers\Api\v2\PlatformController::class, 'show'])->name('show');
            Route::get('/{id}/for-partner', [\App\Http\Controllers\Api\v2\PlatformController::class, 'partnerPlatform'])->name('partner_platform');
            Route::get('/{id}/check-user-role', [\App\Http\Controllers\Api\v2\PlatformController::class, 'checkUserRole'])->name('check_user_role');
            Route::post('/', [\App\Http\Controllers\Api\v2\PlatformController::class, 'store'])->name('store');
            Route::put('/{id}', [\App\Http\Controllers\Api\v2\PlatformController::class, 'update'])->name('update');
            Route::delete('/{id}', [\App\Http\Controllers\Api\v2\PlatformController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('platform-change-requests')->name('platform_change_requests_')->group(function () {
            Route::get('/', [\App\Http\Controllers\Api\v2\PlatformChangeRequestController::class, 'index'])->name('index');
            Route::get('/pending', [\App\Http\Controllers\Api\v2\PlatformChangeRequestController::class, 'pending'])->name('pending');
            Route::get('/pending-count', [\App\Http\Controllers\Api\v2\PlatformChangeRequestController::class, 'pendingCount'])->name('pending_count');
            Route::get('/statistics', [\App\Http\Controllers\Api\v2\PlatformChangeRequestController::class, 'statistics'])->name('statistics');
            Route::get('/{id}', [\App\Http\Controllers\Api\v2\PlatformChangeRequestController::class, 'show'])->name('show');
            Route::post('/', [\App\Http\Controllers\Api\v2\PlatformChangeRequestController::class, 'store'])->name('store');
            Route::post('/{id}/approve', [\App\Http\Controllers\Api\v2\PlatformChangeRequestController::class, 'approve'])->name('approve');
            Route::post('/{id}/reject', [\App\Http\Controllers\Api\v2\PlatformChangeRequestController::class, 'reject'])->name('reject');
            Route::post('/{id}/cancel', [\App\Http\Controllers\Api\v2\PlatformChangeRequestController::class, 'cancel'])->name('cancel');
        });

        Route::prefix('platform-validation-requests')->name('platform_validation_requests_')->group(function () {
            Route::get('/', [\App\Http\Controllers\Api\v2\PlatformValidationRequestController::class, 'index'])->name('index');
            Route::get('/pending', [\App\Http\Controllers\Api\v2\PlatformValidationRequestController::class, 'pending'])->name('pending');
            Route::get('/pending-count', [\App\Http\Controllers\Api\v2\PlatformValidationRequestController::class, 'pendingCount'])->name('pending_count');
            Route::get('/pending-with-total', [\App\Http\Controllers\Api\v2\PlatformValidationRequestController::class, 'pendingWithTotal'])->name('pending_with_total');
            Route::get('/{id}', [\App\Http\Controllers\Api\v2\PlatformValidationRequestController::class, 'show'])->name('show');
            Route::post('/', [\App\Http\Controllers\Api\v2\PlatformValidationRequestController::class, 'store'])->name('store');
            Route::post('/{id}/approve', [\App\Http\Controllers\Api\v2\PlatformValidationRequestController::class, 'approve'])->name('approve');
            Route::post('/{id}/reject', [\App\Http\Controllers\Api\v2\PlatformValidationRequestController::class, 'reject'])->name('reject');
            Route::post('/{id}/cancel', [\App\Http\Controllers\Api\v2\PlatformValidationRequestController::class, 'cancel'])->name('cancel');
        });

        Route::prefix('platform-type-change-requests')->name('platform_type_change_requests_')->group(function () {
            Route::get('/', [\App\Http\Controllers\Api\v2\PlatformTypeChangeRequestController::class, 'index'])->name('index');
            Route::get('/pending', [\App\Http\Controllers\Api\v2\PlatformTypeChangeRequestController::class, 'pending'])->name('pending');
            Route::get('/pending-count', [\App\Http\Controllers\Api\v2\PlatformTypeChangeRequestController::class, 'pendingCount'])->name('pending_count');
            Route::get('/pending-with-total', [\App\Http\Controllers\Api\v2\PlatformTypeChangeRequestController::class, 'pendingWithTotal'])->name('pending_with_total');
            Route::get('/{id}', [\App\Http\Controllers\Api\v2\PlatformTypeChangeRequestController::class, 'show'])->name('show');
            Route::post('/', [\App\Http\Controllers\Api\v2\PlatformTypeChangeRequestController::class, 'store'])->name('store');
            Route::post('/{id}/approve', [\App\Http\Controllers\Api\v2\PlatformTypeChangeRequestController::class, 'approve'])->name('approve');
            Route::post('/{id}/reject', [\App\Http\Controllers\Api\v2\PlatformTypeChangeRequestController::class, 'reject'])->name('reject');
        });

        Route::prefix('assign-platform-roles')->name('assign_platform_roles_')->group(function () {
            Route::get('/', [\App\Http\Controllers\Api\v2\AssignPlatformRoleController::class, 'index'])->name('index');
            Route::post('/{id}/approve', [\App\Http\Controllers\Api\v2\AssignPlatformRoleController::class, 'approve'])->name('approve');
            Route::post('/{id}/reject', [\App\Http\Controllers\Api\v2\AssignPlatformRoleController::class, 'reject'])->name('reject');
        });

        Route::prefix('pending-platform-change-requests-inline')->name('pending_platform_change_requests_inline_')->group(function () {
            Route::get('/', [\App\Http\Controllers\Api\v2\PendingPlatformChangeRequestsInlineController::class, 'index'])->name('index');
            Route::get('/count', [\App\Http\Controllers\Api\v2\PendingPlatformChangeRequestsInlineController::class, 'count'])->name('count');
            Route::get('/with-total', [\App\Http\Controllers\Api\v2\PendingPlatformChangeRequestsInlineController::class, 'withTotal'])->name('with_total');
        });

        Route::prefix('pending-platform-role-assignments-inline')->name('pending_platform_role_assignments_inline_')->group(function () {
            Route::get('/', [\App\Http\Controllers\Api\v2\PendingPlatformRoleAssignmentsInlineController::class, 'index'])->name('index');
            Route::get('/count', [\App\Http\Controllers\Api\v2\PendingPlatformRoleAssignmentsInlineController::class, 'count'])->name('count');
            Route::get('/with-total', [\App\Http\Controllers\Api\v2\PendingPlatformRoleAssignmentsInlineController::class, 'withTotal'])->name('with_total');
        });

        Route::prefix('translale-models')->name('translale_models_')->group(function () {
            Route::get('/', [\App\Http\Controllers\Api\v2\TranslaleModelController::class, 'index'])->name('index');
            Route::get('/all', [\App\Http\Controllers\Api\v2\TranslaleModelController::class, 'all'])->name('all');
            Route::get('/count', [\App\Http\Controllers\Api\v2\TranslaleModelController::class, 'count'])->name('count');
            Route::get('/key-value-arrays', [\App\Http\Controllers\Api\v2\TranslaleModelController::class, 'keyValueArrays'])->name('key_value_arrays');
            Route::get('/search', [\App\Http\Controllers\Api\v2\TranslaleModelController::class, 'search'])->name('search');
            Route::get('/exists', [\App\Http\Controllers\Api\v2\TranslaleModelController::class, 'exists'])->name('exists');
            Route::get('/by-pattern', [\App\Http\Controllers\Api\v2\TranslaleModelController::class, 'byPattern'])->name('by_pattern');
            Route::get('/{id}', [\App\Http\Controllers\Api\v2\TranslaleModelController::class, 'show'])->name('show');
            Route::post('/', [\App\Http\Controllers\Api\v2\TranslaleModelController::class, 'store'])->name('store');
            Route::put('/{id}', [\App\Http\Controllers\Api\v2\TranslaleModelController::class, 'update'])->name('update');
            Route::delete('/{id}', [\App\Http\Controllers\Api\v2\TranslaleModelController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('translate-tabs')->name('translate_tabs_')->group(function () {
            Route::get('/', [\App\Http\Controllers\Api\v2\TranslateTabsController::class, 'index'])->name('index');
            Route::get('/all', [\App\Http\Controllers\Api\v2\TranslateTabsController::class, 'all'])->name('all');
            Route::get('/count', [\App\Http\Controllers\Api\v2\TranslateTabsController::class, 'count'])->name('count');
            Route::get('/statistics', [\App\Http\Controllers\Api\v2\TranslateTabsController::class, 'statistics'])->name('statistics');
            Route::get('/key-value-arrays', [\App\Http\Controllers\Api\v2\TranslateTabsController::class, 'keyValueArrays'])->name('key_value_arrays');
            Route::get('/search', [\App\Http\Controllers\Api\v2\TranslateTabsController::class, 'search'])->name('search');
            Route::get('/exists', [\App\Http\Controllers\Api\v2\TranslateTabsController::class, 'exists'])->name('exists');
            Route::get('/by-pattern', [\App\Http\Controllers\Api\v2\TranslateTabsController::class, 'byPattern'])->name('by_pattern');
            Route::get('/{id}', [\App\Http\Controllers\Api\v2\TranslateTabsController::class, 'show'])->name('show');
            Route::post('/', [\App\Http\Controllers\Api\v2\TranslateTabsController::class, 'store'])->name('store');
            Route::post('/bulk', [\App\Http\Controllers\Api\v2\TranslateTabsController::class, 'bulkStore'])->name('bulk_store');
            Route::put('/{id}', [\App\Http\Controllers\Api\v2\TranslateTabsController::class, 'update'])->name('update');
            Route::delete('/{id}', [\App\Http\Controllers\Api\v2\TranslateTabsController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('translation-merge')->name('translation_merge_')->group(function () {
            Route::post('/merge', [\App\Http\Controllers\Api\v2\TranslationMergeController::class, 'merge'])->name('merge');
            Route::post('/merge-default', [\App\Http\Controllers\Api\v2\TranslationMergeController::class, 'mergeDefault'])->name('merge_default');
            Route::get('/supported-languages', [\App\Http\Controllers\Api\v2\TranslationMergeController::class, 'supportedLanguages'])->name('supported_languages');
            Route::get('/language-name/{code}', [\App\Http\Controllers\Api\v2\TranslationMergeController::class, 'getLanguageName'])->name('language_name');
            Route::get('/source-path/{code}', [\App\Http\Controllers\Api\v2\TranslationMergeController::class, 'getDefaultSourcePath'])->name('source_path');
        });

        Route::prefix('user-guides')->name('user_guides_')->group(function () {
            Route::get('/', [\App\Http\Controllers\Api\v2\UserGuideController::class, 'index'])->name('index');
            Route::get('/all', [\App\Http\Controllers\Api\v2\UserGuideController::class, 'all'])->name('all');
            Route::get('/count', [\App\Http\Controllers\Api\v2\UserGuideController::class, 'count'])->name('count');
            Route::get('/recent', [\App\Http\Controllers\Api\v2\UserGuideController::class, 'recent'])->name('recent');
            Route::get('/search', [\App\Http\Controllers\Api\v2\UserGuideController::class, 'search'])->name('search');
            Route::get('/by-route', [\App\Http\Controllers\Api\v2\UserGuideController::class, 'byRoute'])->name('by_route');
            Route::get('/users/{userId}', [\App\Http\Controllers\Api\v2\UserGuideController::class, 'byUser'])->name('by_user');
            Route::get('/{id}/exists', [\App\Http\Controllers\Api\v2\UserGuideController::class, 'exists'])->name('exists');
            Route::get('/{id}', [\App\Http\Controllers\Api\v2\UserGuideController::class, 'show'])->name('show');
            Route::post('/', [\App\Http\Controllers\Api\v2\UserGuideController::class, 'store'])->name('store');
            Route::put('/{id}', [\App\Http\Controllers\Api\v2\UserGuideController::class, 'update'])->name('update');
            Route::delete('/{id}', [\App\Http\Controllers\Api\v2\UserGuideController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('user-balances')->name('user_balances_')->group(function () {
            // Horizontal Balance Endpoints
            Route::prefix('horizontal')->name('horizontal_')->group(function () {
                Route::get('/{userId}', [\App\Http\Controllers\Api\v2\UserBalancesController::class, 'getHorizontalBalance'])->name('get');
                Route::get('/{userId}/field/{field}', [\App\Http\Controllers\Api\v2\UserBalancesController::class, 'getHorizontalBalanceField'])->name('get_field');
                Route::get('/{userId}/cash', [\App\Http\Controllers\Api\v2\UserBalancesController::class, 'getCashBalance'])->name('cash');
                Route::get('/{userId}/bfss/{type}', [\App\Http\Controllers\Api\v2\UserBalancesController::class, 'getBfssBalance'])->name('bfss');
                Route::get('/{userId}/discount', [\App\Http\Controllers\Api\v2\UserBalancesController::class, 'getDiscountBalance'])->name('discount');
                Route::get('/{userId}/tree', [\App\Http\Controllers\Api\v2\UserBalancesController::class, 'getTreeBalance'])->name('tree');
                Route::get('/{userId}/sms', [\App\Http\Controllers\Api\v2\UserBalancesController::class, 'getSmsBalance'])->name('sms');
                Route::put('/{userId}/calculated', [\App\Http\Controllers\Api\v2\UserBalancesController::class, 'updateCalculatedHorizontal'])->name('update_calculated');
                Route::put('/{userId}/field', [\App\Http\Controllers\Api\v2\UserBalancesController::class, 'updateBalanceField'])->name('update_field');
                Route::post('/{userId}/calculate', [\App\Http\Controllers\Api\v2\UserBalancesController::class, 'calculateNewBalance'])->name('calculate');
            });

            // Vertical Balance Endpoints
            Route::prefix('vertical')->name('vertical_')->group(function () {
                Route::get('/{userId}/all', [\App\Http\Controllers\Api\v2\UserBalancesController::class, 'getAllVerticalBalances'])->name('all');
                Route::get('/{userId}/{balanceId}', [\App\Http\Controllers\Api\v2\UserBalancesController::class, 'getVerticalBalance'])->name('get');
                Route::put('/{userId}/update-after-operation', [\App\Http\Controllers\Api\v2\UserBalancesController::class, 'updateVerticalBalanceAfterOperation'])->name('update_after_operation');
                Route::put('/{userId}/calculated', [\App\Http\Controllers\Api\v2\UserBalancesController::class, 'updateCalculatedVertical'])->name('update_calculated');
            });
        });

        Route::prefix('communication-board')->name('communication_board_')->group(function () {
            Route::get('/', [\App\Http\Controllers\Api\v2\CommunicationBoardController::class, 'index'])->name('index');
            Route::get('/all', [\App\Http\Controllers\Api\v2\CommunicationBoardController::class, 'all'])->name('all');
        });

        Route::prefix('commission-breakdowns')->name('commission_breakdowns_')->group(function () {
            Route::get('/by-deal', [\App\Http\Controllers\Api\v2\CommissionBreakDownController::class, 'getByDeal'])->name('by_deal');
            Route::get('/deals/{dealId}/totals', [\App\Http\Controllers\Api\v2\CommissionBreakDownController::class, 'calculateTotals'])->name('totals');
            Route::get('/{id}', [\App\Http\Controllers\Api\v2\CommissionBreakDownController::class, 'show'])->name('show');
            Route::post('/', [\App\Http\Controllers\Api\v2\CommissionBreakDownController::class, 'store'])->name('store');
            Route::put('/{id}', [\App\Http\Controllers\Api\v2\CommissionBreakDownController::class, 'update'])->name('update');
            Route::delete('/{id}', [\App\Http\Controllers\Api\v2\CommissionBreakDownController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('events')->name('events_')->group(function () {
            Route::get('/', [\App\Http\Controllers\Api\v2\EventController::class, 'index'])->name('index');
            Route::get('/all', [\App\Http\Controllers\Api\v2\EventController::class, 'all'])->name('all');
            Route::get('/enabled', [\App\Http\Controllers\Api\v2\EventController::class, 'enabled'])->name('enabled');
            Route::get('/{id}', [\App\Http\Controllers\Api\v2\EventController::class, 'show'])->name('show');
            Route::get('/{id}/with-relationships', [\App\Http\Controllers\Api\v2\EventController::class, 'showWithRelationships'])->name('show_with_relationships');
            Route::get('/{id}/with-main-image', [\App\Http\Controllers\Api\v2\EventController::class, 'showWithMainImage'])->name('show_with_main_image');
            Route::get('/{id}/has-user-liked', [\App\Http\Controllers\Api\v2\EventController::class, 'hasUserLiked'])->name('has_user_liked');
            Route::post('/', [\App\Http\Controllers\Api\v2\EventController::class, 'store'])->name('store');
            Route::post('/{id}/like', [\App\Http\Controllers\Api\v2\EventController::class, 'addLike'])->name('add_like');
            Route::delete('/{id}/like', [\App\Http\Controllers\Api\v2\EventController::class, 'removeLike'])->name('remove_like');
            Route::post('/{id}/comment', [\App\Http\Controllers\Api\v2\EventController::class, 'addComment'])->name('add_comment');
            Route::put('/{id}', [\App\Http\Controllers\Api\v2\EventController::class, 'update'])->name('update');
            Route::delete('/{id}', [\App\Http\Controllers\Api\v2\EventController::class, 'destroy'])->name('destroy');
        });
    });
