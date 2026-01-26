<?php

namespace App\Services\FinancialRequest;

use App\Models\detail_financial_request;
use App\Models\FinancialRequest;
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

    /**
     * Reject a financial request
     * Updates the user's response to rejected (2)
     * If all users have rejected, marks the main request as refused (status 5)
     *
     * @param string $numeroReq
     * @param int $rejectingUserId
     * @return bool
     * @throws \Exception
     */
    public function rejectFinancialRequest(string $numeroReq, int $rejectingUserId): bool
    {
        try {
            DB::beginTransaction();

            detail_financial_request::where('numeroRequest', '=', $numeroReq)
                ->where('idUser', '=', $rejectingUserId)
                ->update([
                    'response' => 2,
                    'dateResponse' => date(config('app.date_format'))
                ]);

            $remainingPending = detail_financial_request::where('numeroRequest', '=', $numeroReq)
                ->where('response', '=', null)
                ->count();

            if ($remainingPending == 0) {
                FinancialRequest::where('numeroReq', '=', $numeroReq)
                    ->update([
                        'status' => 5,
                        'idUserAccepted' => $rejectingUserId,
                        'dateAccepted' => date(config('app.date_format'))
                    ]);
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error rejecting financial request', [
                'numeroReq' => $numeroReq,
                'rejectingUserId' => $rejectingUserId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Cancel a financial request
     * Updates the request status to canceled (3)
     *
     * @param string $numeroReq
     * @param int $cancelingUserId
     * @return bool
     * @throws \Exception
     */
    public function cancelFinancialRequest(string $numeroReq, int $cancelingUserId): bool
    {
        try {
            DB::beginTransaction();

            FinancialRequest::where('numeroReq', '=', $numeroReq)
                ->update([
                    'status' => 3,
                    'idUserAccepted' => $cancelingUserId,
                    'dateAccepted' => date(config('app.date_format'))
                ]);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error canceling financial request', [
                'numeroReq' => $numeroReq,
                'cancelingUserId' => $cancelingUserId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }


    /**
     * Confirm a financial request with validation and balance operations
     *
     * @param int $val The confirmation type (2 = from BFS)
     * @param string $num The request number
     * @param string $secCode The security code
     * @param int $userId The user ID confirming the request
     * @param \App\Services\UserBalancesHelper $userBalancesHelper
     * @return array ['success' => bool, 'message' => string, 'redirect' => string|null, 'redirectParams' => array]
     * @throws \Exception
     */
    public function confirmFinancialRequest(
        int $val,
        string $num,
        string $secCode,
        int $userId,
        \App\Services\UserBalancesHelper $userBalancesHelper
    ): array
    {
        $financialRequest = $this->getByNumeroReq($num);

        // Validate financial request exists and is open
        if (!$financialRequest || $financialRequest->status != 0) {
            return [
                'success' => false,
                'message' => \Illuminate\Support\Facades\Lang::get('Invalid financial request'),
                'type' => 'danger',
                'redirect' => 'accept_financial_request',
                'redirectParams' => ['numeroReq' => $num]
            ];
        }

        // Validate security code
        if ($financialRequest->securityCode == "" || $financialRequest->securityCode != $secCode) {
            return [
                'success' => false,
                'message' => \Illuminate\Support\Facades\Lang::get('Failed security code'),
                'type' => 'danger',
                'redirect' => 'accept_financial_request',
                'redirectParams' => ['numeroReq' => $num]
            ];
        }

        $param = ['montant' => $financialRequest->amount, 'recipient' => $financialRequest->idSender];
        $userCurrentBalancehorisontal = \App\Services\Balances\Balances::getStoredUserBalances($userId);
        $bfs100 = $userCurrentBalancehorisontal->getBfssBalance(\App\Models\BFSsBalances::BFS_100);
        $financialRequestAmount = floatval($financialRequest->amount);

        // If confirming from BFS (val == 2), check BFS balance
        if ($val == 2) {
            if ($bfs100 < $financialRequestAmount) {
                $montant = $financialRequestAmount - $bfs100;
                return [
                    'success' => false,
                    'message' => trans('Insufficient BFS 100 balance') . ' : ' . $bfs100 . ' > ' . $montant,
                    'type' => 'warning',
                    'redirect' => 'financial_transaction',
                    'redirectParams' => ['filter' => 5, 'FinRequestN' => $financialRequest->numeroReq]
                ];
            }

            // Add balance operation
            $userBalancesHelper->AddBalanceByEvent(
                \App\Enums\EventBalanceOperationEnum::SendToPublicFromBFS,
                $userId,
                $param
            );
        }

        // Verify detail request exists
        $detailRequst = $this->getDetailRequest($num, $userId);
        if (!$detailRequst) {
            return [
                'success' => false,
                'message' => \Illuminate\Support\Facades\Lang::get('Invalid financial request'),
                'type' => 'danger',
                'redirect' => 'accept_financial_request',
                'redirectParams' => ['numeroReq' => $num]
            ];
        }

        // Accept the financial request
        $this->acceptFinancialRequest($num, $userId);

        return [
            'success' => true,
            'message' => \Illuminate\Support\Facades\Lang::get('accepted Request'),
            'type' => 'success',
            'redirect' => 'financial_transaction',
            'redirectParams' => ['filter' => 5]
        ];
    }

    public function createFinancialRequest($idUser, $amount, $selectedUsers, $securityCode)
    {

        $lastnumero = 0;
        $lastRequest = DB::table('financial_request')
            ->latest('numeroReq')
            ->first();
        if ($lastRequest) {
            $lastnumero = $lastRequest->numeroReq;
        }
        $date = date('Y-m-d H:i:s');
        $year = date('y', strtotime($date));
        $numer = (int)substr($lastnumero, -6);
        $ref = date('y') . substr((1000000 + $numer + 1), 1, 6);
        $data = [];
        foreach ($selectedUsers as $val) {
            if ($val != $idUser) {
                $new = ['numeroRequest' => $ref, 'idUser' => $val];
                array_push($data, $new);
            }
        }
        detail_financial_request::insert($data);
        DB::table('financial_request')
            ->insert([
                'numeroReq' => $ref,
                'idSender' => $idUser,
                'Date' => $date,
                'amount' => $amount,
                'status' => '0',
                'securityCode' => $securityCode
            ]);


    }

    /**
     * Validate and reject a financial request with full validation
     *
     * @param string $numeroReq Request number
     * @param string $idUser User's business ID
     * @return array Result array with success status and message
     */
    public function validateAndRejectRequest(string $numeroReq, string $idUser): array
    {
        try {
            // Get the financial request
            $financialRequest = $this->getByNumeroReq($numeroReq);

            if (!$financialRequest || $financialRequest->status != 0) {
                return [
                    'success' => false,
                    'message' => 'Invalid financial request'
                ];
            }

            // Check if user is part of this request
            $detailRequest = $this->getDetailRequest($numeroReq, $idUser);

            if (!$detailRequest) {
                return [
                    'success' => false,
                    'message' => 'Invalid details financial request'
                ];
            }

            // Reject the request
            $this->rejectFinancialRequest($numeroReq, $idUser);

            return [
                'success' => true,
                'message' => 'Financial request rejected successfully'
            ];

        } catch (\Exception $exception) {
            Log::error('Error validating and rejecting financial request: ' . $exception->getMessage(), [
                'numeroReq' => $numeroReq,
                'idUser' => $idUser
            ]);

            return [
                'success' => false,
                'message' => 'Error rejecting financial request'
            ];
        }
    }

    /**
     * Validate financial request for acceptance
     *
     * @param string $numeroReq Request number
     * @return array Result array with success status and message
     */
    public function validateRequestForAcceptance(string $numeroReq): array
    {
        try {
            // Get the financial request
            $financialRequest = $this->getByNumeroReq($numeroReq);

            if (!$financialRequest || $financialRequest->status != 0) {
                return [
                    'success' => false,
                    'message' => 'Invalid details financial request'
                ];
            }

            return [
                'success' => true,
                'message' => 'Request is valid for acceptance',
                'request' => $financialRequest
            ];

        } catch (\Exception $exception) {
            Log::error('Error validating financial request for acceptance: ' . $exception->getMessage(), [
                'numeroReq' => $numeroReq
            ]);

            return [
                'success' => false,
                'message' => 'Error validating financial request'
            ];
        }
    }
}

