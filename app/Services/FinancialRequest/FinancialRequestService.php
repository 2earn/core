<?php

namespace App\Services\FinancialRequest;

use Core\Models\detail_financial_request;
use Core\Models\FinancialRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

    /**
     * Get incoming recharge requests for a user
     *
     * @param int $userId
     * @return \Illuminate\Support\Collection
     */
    public function getRechargeRequestsIn(int $userId)
    {
        return DB::table('recharge_requests')
            ->select('recharge_requests.Date', 'users.name as USER', 'recharge_requests.payeePhone as userphone', 'recharge_requests.amount')
            ->leftJoin('users', 'users.idUser', '=', 'recharge_requests.idPayee')
            ->where('recharge_requests.idUser', $userId)
            ->get();
    }

    /**
     * Get outgoing recharge requests for a user
     *
     * @param int $userId
     * @return \Illuminate\Support\Collection
     */
    public function getRechargeRequestsOut(int $userId)
    {
        return DB::table('recharge_requests')
            ->select('recharge_requests.Date', 'users.name as USER', 'recharge_requests.payeePhone as userphone', 'recharge_requests.amount')
            ->leftJoin('users', 'users.idUser', '=', 'recharge_requests.idPayee')
            ->where('recharge_requests.idSender', $userId)
            ->get();
    }

    /**
     * Get financial request by numero request
     *
     * @param string $numeroReq
     * @return FinancialRequest|null
     */
    public function getByNumeroReq(string $numeroReq): ?FinancialRequest
    {
        return FinancialRequest::where('numeroReq', '=', $numeroReq)->first();
    }

    /**
     * Get financial request with user details
     *
     * @param string $numeroReq
     * @return mixed
     */
    public function getRequestWithUserDetails(string $numeroReq)
    {
        return FinancialRequest::join('users', 'financial_request.idSender', '=', 'users.idUser')
            ->where('numeroReq', '=', $numeroReq)
            ->get(['financial_request.numeroReq', 'financial_request.date', 'users.name', 'users.mobile', 'financial_request.amount', 'financial_request.status'])
            ->first();
    }

    /**
     * Get detail financial request for a specific user and request
     *
     * @param string $numeroReq
     * @param int $userId
     * @return detail_financial_request|null
     */
    public function getDetailRequest(string $numeroReq, int $userId): ?detail_financial_request
    {
        return detail_financial_request::where('numeroRequest', '=', $numeroReq)
            ->where('idUser', '=', $userId)
            ->first();
    }

    /**
     * Accept a financial request
     * Performs three database operations in a transaction:
     * 1. Reject all other pending responses
     * 2. Accept the current user's response
     * 3. Update the main request status
     *
     * @param string $numeroReq
     * @param int $acceptingUserId
     * @return bool
     * @throws \Exception
     */
    public function acceptFinancialRequest(string $numeroReq, int $acceptingUserId): bool
    {
        try {
            DB::beginTransaction();

            detail_financial_request::where('numeroRequest', '=', $numeroReq)
                ->where('response', '=', null)
                ->update([
                    'response' => 3,
                    'dateResponse' => date(config('app.date_format'))
                ]);

            detail_financial_request::where('numeroRequest', '=', $numeroReq)
                ->where('idUser', '=', $acceptingUserId)
                ->update([
                    'response' => 1,
                    'dateResponse' => date(config('app.date_format'))
                ]);

            FinancialRequest::where('numeroReq', '=', $numeroReq)
                ->update([
                    'status' => 1,
                    'idUserAccepted' => $acceptingUserId,
                    'dateAccepted' => date(config('app.date_format'))
                ]);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error accepting financial request', [
                'numeroReq' => $numeroReq,
                'acceptingUserId' => $acceptingUserId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
}

