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

    /**
     * Get financial requests sent to the user
     *
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getRequestsToUser(int $userId)
    {
        return detail_financial_request::join('financial_request', 'financial_request.numeroReq', '=', 'detail_financial_request.numeroRequest')
            ->join('users', 'financial_request.idSender', '=', 'users.idUser')
            ->where('detail_financial_request.idUser', $userId)
            ->orderBy('financial_request.date', 'desc')
            ->get(['financial_request.numeroReq', 'financial_request.date', 'users.name', 'users.mobile', 'financial_request.amount', 'financial_request.status']);
    }

    /**
     * Count open financial requests sent to the user
     *
     * @param int $userId
     * @return int
     */
    public function countRequestsInOpen(int $userId): int
    {
        return detail_financial_request::join('financial_request', 'financial_request.numeroReq', '=', 'detail_financial_request.numeroRequest')
            ->where('detail_financial_request.idUser', $userId)
            ->where('financial_request.Status', 0)
            ->where('detail_financial_request.vu', 0)
            ->count();
    }

    /**
     * Count accepted financial requests sent by the user
     *
     * @param int $userId
     * @return int
     */
    public function countRequestsOutAccepted(int $userId): int
    {
        return FinancialRequest::where('financial_request.idSender', $userId)
            ->where('financial_request.Status', 1)
            ->where('financial_request.vu', 0)
            ->count();
    }

    /**
     * Count refused financial requests sent by the user
     *
     * @param int $userId
     * @return int
     */
    public function countRequestsOutRefused(int $userId): int
    {
        return FinancialRequest::where('financial_request.idSender', $userId)
            ->where('financial_request.Status', 5)
            ->where('financial_request.vu', 0)
            ->count();
    }

    /**
     * Get financial requests sent by the user
     *
     * @param int $userId
     * @param bool $showCanceled Whether to include canceled requests (status 3)
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getRequestsFromUser(int $userId, bool $showCanceled = false)
    {
        $query = FinancialRequest::where('financial_request.idSender', $userId);

        if (!$showCanceled) {
            $query->where('financial_request.Status', '!=', '3');
        }

        return $query->join('users as u1', 'financial_request.idSender', '=', 'u1.idUser')
            ->with('details', 'details.User')
            ->orderBy('financial_request.date', 'desc')
            ->get(['financial_request.numeroReq', 'financial_request.date', 'u1.name', 'u1.mobile', 'financial_request.amount', 'financial_request.status as FStatus', 'financial_request.securityCode']);
    }
}

