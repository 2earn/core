<?php

namespace App\Services\FinancialRequest;

use Core\Models\detail_financial_request;
use Core\Models\FinancialRequest;

class FinancialRequestService
{
    /**
     * Reset outgoing notification status for accepted and refused requests
     *
     * @param int $userId
     * @return int Number of updated rows
     */
    public function resetOutGoingNotification(int $userId): int
    {
        $acceptedCount = FinancialRequest::where('financial_request.idSender', $userId)
            ->where('financial_request.Status', 1)
            ->where('financial_request.vu', 0)
            ->update(['financial_request.vu' => 1]);

        $refusedCount = FinancialRequest::where('financial_request.idSender', $userId)
            ->where('financial_request.Status', 5)
            ->where('financial_request.vu', 0)
            ->update(['financial_request.vu' => 1]);

        return $acceptedCount + $refusedCount;
    }

    /**
     * Reset incoming notification status for financial request details
     *
     * @param int $userId
     * @return int Number of updated rows
     */
    public function resetInComingNotification(int $userId): int
    {
        return detail_financial_request::where('detail_financial_request.idUser', $userId)
            ->where('detail_financial_request.vu', 0)
            ->update(['detail_financial_request.vu' => 1]);
    }
}

