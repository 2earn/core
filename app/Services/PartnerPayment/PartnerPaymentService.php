<?php

namespace App\Services\PartnerPayment;

use App\Models\PartnerPayment;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PartnerPaymentService
{
    /**
     * Create a new partner payment.
     *
     * @param array $data
     * @return PartnerPayment
     * @throws \Exception
     */
    public function create(array $data): PartnerPayment
    {
        try {
            DB::beginTransaction();

            $payment = PartnerPayment::create([
                'amount' => $data['amount'],
                'method' => $data['method'],
                'payment_date' => $data['payment_date'] ?? now(),
                'partner_id' => $data['partner_id'],
                'validated_by' => $data['validated_by'] ?? null,
                'validated_at' => $data['validated_at'] ?? null,
            ]);

            DB::commit();

            return $payment;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create partner payment: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Update a partner payment.
     *
     * @param int $paymentId
     * @param array $data
     * @return PartnerPayment
     * @throws \Exception
     */
    public function update(int $paymentId, array $data): PartnerPayment
    {
        try {
            $payment = PartnerPayment::findOrFail($paymentId);

            $payment->update(array_filter([
                'amount' => $data['amount'] ?? $payment->amount,
                'method' => $data['method'] ?? $payment->method,
                'payment_date' => $data['payment_date'] ?? $payment->payment_date,
                'partner_id' => $data['partner_id'] ?? $payment->partner_id,
            ]));

            return $payment->fresh();
        } catch (\Exception $e) {
            Log::error('Failed to update partner payment: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Validate a partner payment.
     *
     * @param int $paymentId
     * @param int $validatorId
     * @return PartnerPayment
     * @throws \Exception
     */
    public function validatePayment(int $paymentId, int $validatorId): PartnerPayment
    {
        try {
            DB::beginTransaction();

            $payment = PartnerPayment::findOrFail($paymentId);

            if ($payment->isValidated()) {
                throw new \Exception('Payment is already validated');
            }

            $payment->validate($validatorId);

            DB::commit();

            return $payment->fresh();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to validate partner payment: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Reject a partner payment.
     *
     * @param int $paymentId
     * @param int $rejectorId
     * @param string $reason
     * @return PartnerPayment
     * @throws \Exception
     */
    public function rejectPayment(int $paymentId, int $rejectorId, string $reason = ''): PartnerPayment
    {
        try {
            DB::beginTransaction();

            $payment = PartnerPayment::findOrFail($paymentId);

            if ($payment->isValidated()) {
                throw new \Exception('Cannot reject an already validated payment');
            }

            if ($payment->isRejected()) {
                throw new \Exception('Payment is already rejected');
            }

            $payment->reject($rejectorId, $reason);

            DB::commit();

            return $payment->fresh();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to reject partner payment: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get partner payments by partner ID.
     *
     * @param int $partnerId
     * @param array $filters
     * @return Collection
     */
    public function getByPartnerId(int $partnerId, array $filters = []): Collection
    {
        $query = PartnerPayment::where('partner_id', $partnerId)
            ->with(['partner',  'validator']);

        if (isset($filters['validated']) && $filters['validated'] === true) {
            $query->whereNotNull('validated_at');
        } elseif (isset($filters['validated']) && $filters['validated'] === false) {
            $query->whereNull('validated_at');
        }

        if (isset($filters['method'])) {
            $query->where('method', $filters['method']);
        }

        if (isset($filters['from_date'])) {
            $query->where('payment_date', '>=', $filters['from_date']);
        }

        if (isset($filters['to_date'])) {
            $query->where('payment_date', '<=', $filters['to_date']);
        }

        return $query->orderBy('payment_date', 'desc')->get();
    }


    /**
     * Get partner payment by ID.
     *
     * @param int $paymentId
     * @return PartnerPayment
     * @throws \Exception
     */
    public function getById($paymentId): PartnerPayment
    {
        return PartnerPayment::with(['partner', 'validator'])
            ->findOrFail($paymentId);
    }

    /**
     * Get all payments with filters and pagination.
     * Super admin sees all payments, others see only payments where they are the receiver (partner).
     *
     * @param array $filters
     * @param int $perPage
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getPayments(array $filters = [], int $perPage = 10)
    {
        $query = PartnerPayment::with(['partner', 'validator', 'rejector']);

        // If not super admin, only show payments where user is the receiver (partner)
        if (!\App\Models\User::isSuperAdmin()) {
            $query->where('partner_id', auth()->id());
        }

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('id', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('amount', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('method', 'like', '%' . $filters['search'] . '%')
                    ->orWhereHas('user', function ($userQuery) use ($filters) {
                        $userQuery->where('name', 'like', '%' . $filters['search'] . '%');
                    })
                    ->orWhereHas('partner', function ($partnerQuery) use ($filters) {
                        $partnerQuery->where('name', 'like', '%' . $filters['search'] . '%');
                    });
            });
        }

        if (isset($filters['statusFilter'])) {
            if ($filters['statusFilter'] === 'pending') {
                $query->whereNull('validated_at')->whereNull('rejected_at');
            } elseif ($filters['statusFilter'] === 'validated') {
                $query->whereNotNull('validated_at');
            } elseif ($filters['statusFilter'] === 'rejected') {
                $query->whereNotNull('rejected_at');
            }
        }

        if (!empty($filters['methodFilter'])) {
            $query->where('method', $filters['methodFilter']);
        }

        if (!empty($filters['partnerFilter'])) {
            $query->where('partner_id', $filters['partnerFilter']);
        }

        if (!empty($filters['fromDate'])) {
            $query->where('payment_date', '>=', $filters['fromDate']);
        }

        if (!empty($filters['toDate'])) {
            $query->where('payment_date', '<=', $filters['toDate']);
        }

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    /**
     * Delete a partner payment.
     *
     * @param int $paymentId
     * @return bool
     * @throws \Exception
     */
    public function delete(int $paymentId): bool
    {
        try {
            DB::beginTransaction();

            $payment = PartnerPayment::findOrFail($paymentId);

            if ($payment->isValidated()) {
                throw new \Exception('Cannot delete a validated payment');
            }

            $deleted = $payment->delete();

            DB::commit();

            return $deleted;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to delete partner payment: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get total payments for a partner.
     *
     * @param int $partnerId
     * @param bool $validatedOnly
     * @return float
     */
    public function getTotalPaymentsByPartner(int $partnerId, bool $validatedOnly = false): float
    {
        $query = PartnerPayment::where('partner_id', $partnerId);

        if ($validatedOnly) {
            $query->whereNotNull('validated_at');
        }

        return (float) $query->sum('amount');
    }


    /**
     * Get pending payments (not validated).
     *
     * @return Collection
     */
    public function getPendingPayments(): Collection
    {
        return PartnerPayment::whereNull('validated_at')
            ->with(['user', 'partner'])
            ->orderBy('payment_date', 'asc')
            ->get();
    }

    /**
     * Get validated payments.
     *
     * @param array $filters
     * @return Collection
     */
    public function getValidatedPayments(array $filters = []): Collection
    {
        $query = PartnerPayment::whereNotNull('validated_at')
            ->with(['user', 'partner', 'validator']);

        if (isset($filters['from_date'])) {
            $query->where('validated_at', '>=', $filters['from_date']);
        }

        if (isset($filters['to_date'])) {
            $query->where('validated_at', '<=', $filters['to_date']);
        }

        return $query->orderBy('validated_at', 'desc')->get();
    }

    /**
     * Get statistics for partner payments.
     *
     * @return array
     */
    public function getStats(): array
    {
        return [
            'total_payments' => PartnerPayment::count(),
            'pending_payments' => PartnerPayment::whereNull('validated_at')->whereNull('rejected_at')->count(),
            'validated_payments' => PartnerPayment::whereNotNull('validated_at')->count(),
            'rejected_payments' => PartnerPayment::whereNotNull('rejected_at')->count(),
            'total_amount' => PartnerPayment::whereNotNull('validated_at')->sum('amount'),
            'pending_amount' => PartnerPayment::whereNull('validated_at')->whereNull('rejected_at')->sum('amount'),
        ];
    }

    /**
     * Get distinct payment methods.
     *
     * @return Collection
     */
    public function getPaymentMethods()
    {
        return PartnerPayment::distinct()->pluck('method');
    }
}

