<?php

namespace App\Http\Controllers;

use Core\Models\detail_financial_request;
use Core\Models\FinancialRequest;
use Core\Services\settingsManager;
use Illuminate\Http\Request;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class FinancialRequestController extends Controller
{
    //
    public function resetOutGoingNotification(settingsManager $settingsManager)
    {

        $userAuth = $settingsManager->getAuthUser();
        if (!$userAuth) abort(404);
        $requestOutAccepted = FinancialRequest::where('financial_request.idSender', $userAuth->idUser)
            ->where('financial_request.Status', 1)
            ->where('financial_request.vu', 0)
            ->update(['financial_request.vu' => 1]);
        $requestOutRefused = FinancialRequest::where('financial_request.idSender', $userAuth->idUser)
            ->where('financial_request.Status', 5)
            ->where('financial_request.vu', 0)
            ->update(['financial_request.vu' => 1]);
    }

    public function resetInComingNotification(settingsManager $settingsManager)
    {
        $userAuth = $settingsManager->getAuthUser();
        if (!$userAuth) abort(404);
          detail_financial_request::where('detail_financial_request.idUser', $userAuth->idUser)
            ->where('detail_financial_request.vu', 0)
            ->update(['detail_financial_request.vu' => 1]);
    }
    public function resetIn()
    {

    }
}
