<?php

namespace App\Http\Controllers\Api\v2;

use App\Http\Controllers\Controller;
use App\Services\PartnerPayment\PartnerPaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PartnerPaymentController extends Controller
{
    private PartnerPaymentService $partnerPaymentService;

    public function __construct(PartnerPaymentService $partnerPaymentService)
    {
        $this->partnerPaymentService = $partnerPaymentService;
    }

    /**
     * Get all payments with filters (paginated)
     */
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'search' => 'nullable|string',
            'status_filter' => 'nullable|in:all,pending,validated,rejected',
            'method_filter' => 'nullable|string',
            'partner_filter' => 'nullable|integer|exists:users,id',
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date',
            'per_page' => 'nullable|integer|min:1|max:100'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $filters = [
                'search' => $request->input('search'),
                'statusFilter' => $request->input('status_filter'),
                'methodFilter' => $request->input('method_filter'),
                'partnerFilter' => $request->input('partner_filter'),
                'fromDate' => $request->input('from_date'),
                'toDate' => $request->input('to_date'),
            ];

            $perPage = $request->input('per_page', 10);
            $payments = $this->partnerPaymentService->getPayments($filters, $perPage);

            return response()->json([
                'status' => true,
                'data' => $payments->items(),
                'pagination' => [
                    'current_page' => $payments->currentPage(),
                    'per_page' => $payments->perPage(),
                    'total' => $payments->total(),
                    'last_page' => $payments->lastPage()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Get a single payment by ID
     */
    public function show(int $id)
    {
        try {
            $payment = $this->partnerPaymentService->getById($id);
            return response()->json(['status' => true, 'data' => $payment]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['status' => false, 'message' => 'Payment not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Get payments by partner ID
     */
    public function getByPartnerId(Request $request, int $partnerId)
    {
        $validator = Validator::make($request->all(), [
            'validated' => 'nullable|boolean',
            'method' => 'nullable|string',
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $filters = array_filter([
                'validated' => $request->input('validated'),
                'method' => $request->input('method'),
                'from_date' => $request->input('from_date'),
                'to_date' => $request->input('to_date'),
            ]);

            $payments = $this->partnerPaymentService->getByPartnerId($partnerId, $filters);
            return response()->json(['status' => true, 'data' => $payments]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Create a new payment
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:0',
            'method' => 'required|string|max:50',
            'payment_date' => 'nullable|date',
            'partner_id' => 'required|integer|exists:users,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $payment = $this->partnerPaymentService->create($request->all());
            return response()->json(['status' => true, 'data' => $payment], 201);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Update a payment
     */
    public function update(Request $request, int $id)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'nullable|numeric|min:0',
            'method' => 'nullable|string|max:50',
            'payment_date' => 'nullable|date',
            'partner_id' => 'nullable|integer|exists:users,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $payment = $this->partnerPaymentService->update($id, $request->all());
            return response()->json(['status' => true, 'data' => $payment, 'message' => 'Payment updated successfully']);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['status' => false, 'message' => 'Payment not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Delete a payment
     */
    public function destroy(int $id)
    {
        try {
            $result = $this->partnerPaymentService->delete($id);

            if (!$result) {
                return response()->json(['status' => false, 'message' => 'Failed to delete payment'], 400);
            }

            return response()->json(['status' => true, 'message' => 'Payment deleted successfully']);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['status' => false, 'message' => 'Payment not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Validate a payment
     */
    public function validatePayment(Request $request, int $id)
    {
        $validator = Validator::make($request->all(), [
            'validator_id' => 'required|integer|exists:users,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $payment = $this->partnerPaymentService->validatePayment($id, $request->input('validator_id'));
            return response()->json(['status' => true, 'data' => $payment, 'message' => 'Payment validated successfully']);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['status' => false, 'message' => 'Payment not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Reject a payment
     */
    public function rejectPayment(Request $request, int $id)
    {
        $validator = Validator::make($request->all(), [
            'rejector_id' => 'required|integer|exists:users,id',
            'reason' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $payment = $this->partnerPaymentService->rejectPayment(
                $id,
                $request->input('rejector_id'),
                $request->input('reason', '')
            );
            return response()->json(['status' => true, 'data' => $payment, 'message' => 'Payment rejected successfully']);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['status' => false, 'message' => 'Payment not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Get total payments by partner
     */
    public function getTotalByPartner(Request $request, int $partnerId)
    {
        $validator = Validator::make($request->all(), [
            'validated_only' => 'nullable|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $validatedOnly = $request->input('validated_only', false);
            $total = $this->partnerPaymentService->getTotalPaymentsByPartner($partnerId, $validatedOnly);
            return response()->json(['status' => true, 'total' => $total]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Get pending payments
     */
    public function getPending()
    {
        try {
            $payments = $this->partnerPaymentService->getPendingPayments();
            return response()->json(['status' => true, 'data' => $payments]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Get validated payments
     */
    public function getValidated(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $filters = array_filter([
                'from_date' => $request->input('from_date'),
                'to_date' => $request->input('to_date'),
            ]);

            $payments = $this->partnerPaymentService->getValidatedPayments($filters);
            return response()->json(['status' => true, 'data' => $payments]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Get payment statistics
     */
    public function getStats()
    {
        try {
            $stats = $this->partnerPaymentService->getStats();
            return response()->json(['status' => true, 'data' => $stats]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Get payment methods
     */
    public function getPaymentMethods()
    {
        try {
            $methods = $this->partnerPaymentService->getPaymentMethods();
            return response()->json(['status' => true, 'data' => $methods]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }
}

