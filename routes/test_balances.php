<?php

use Illuminate\Support\Facades\Route;

Route::get('/test-user-balances', function () {
    $horizontalService = app(\App\Services\UserBalances\UserCurrentBalanceHorisontalService::class);
    $verticalService = app(\App\Services\UserBalances\UserCurrentBalanceVerticalService::class);
    $helperService = app(\App\Services\UserBalances\UserBalancesHelper::class);

    return response()->json([
        'status' => 'success',
        'services' => [
            'horizontal' => get_class($horizontalService),
            'vertical' => get_class($verticalService),
            'helper' => get_class($helperService)
        ]
    ]);
});

