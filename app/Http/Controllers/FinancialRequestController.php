<?php

namespace App\Http\Controllers;

use App\Services\FinancialRequest\FinancialRequestService;
use Core\Services\settingsManager;

class FinancialRequestController extends Controller
{
    public function __construct(
        private readonly FinancialRequestService $financialRequestService
    ) {
    }

    public function resetOutGoingNotification(settingsManager $settingsManager)
    {
        $userAuth = $settingsManager->getAuthUser();
        if (!$userAuth) abort(404);

        $this->financialRequestService->resetOutGoingNotification($userAuth->idUser);
    }

    public function resetInComingNotification(settingsManager $settingsManager)
    {
        $userAuth = $settingsManager->getAuthUser();
        if (!$userAuth) abort(404);

        $this->financialRequestService->resetInComingNotification($userAuth->idUser);
    }

    public function resetIn()
    {

    }
}
