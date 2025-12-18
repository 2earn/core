<?php

namespace App\Http\Controllers\Api\partner;

use App\Http\Controllers\Controller;
use App\Models\PartnerPayment;
use App\Services\PartnerPayment\PartnerPaymentService;
use Core\Models\FinancialRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PartnerPaymentController extends Controller
{
    private const LOG_PREFIX = '[PartnerPaymentController] ';

    protected $partnerPaymentService;

    public function __construct(PartnerPaymentService $partnerPaymentService)
    {
        $this->middleware('check.url');
        $this->partnerPaymentService = $partnerPaymentService;
    }

    /**
     * Get partner payments list with filtering
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer|exists:users,id',
            'partner_id' => 'nullable|integer|exists:users,id',
            'status' => 'nullable|in:all,pending,validated',
            'method' => 'nullable|string|max:50',
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date|after_or_equal:from_date',
            'page' => 'nullable|integer|min:1',
            'limit' => 'nullable|integer|min:1|max:100',
        ]);

        if ($validator->fails()) {
            Log::error(self::LOG_PREFIX . 'Validation failed for index', ['errors' => $validator->errors()]);
            return response()->json([
                'status' => 'Failed',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $userId = $request->input('user_id');
            $partnerId = $request->input('partner_id');
            $status = $request->input('status', 'all');
            $method = $request->input('method');
            $fromDate = $request->input('from_date');
            $toDate = $request->input('to_date');
            $page = $request->input('page', 1);
            $limit = $request->input('limit', 15);

            // Verify user is a platform partner
            $isPartner = $this->verifyUserIsPartner($userId);
            if (!$isPartner) {
                return response()->json([
                    'status' => 'Failed',
                    'message' => 'User is not a platform partner'
                ], Response::HTTP_FORBIDDEN);
            }

            // Build query
            $query = PartnerPayment::with(['user', 'partner', 'validator']);

            // Filter by partner_id (current user or specific partner)
            if ($partnerId) {
                $query->where('partner_id', $partnerId);
            } else {
                // Show payments where current user is the partner
                $query->where('partner_id', $userId);
            }

            // Status filter
            if ($status === 'pending') {
                $query->whereNull('validated_at');
            } elseif ($status === 'validated') {
                $query->whereNotNull('validated_at');
            }

            // Method filter
            if ($method) {
                $query->where('method', $method);
            }

            // Date range filters
            if ($fromDate) {
                $query->where('payment_date', '>=', $fromDate);
            }
            if ($toDate) {
                $query->where('payment_date', '<=', $toDate);
            }

            // Get total count before pagination
            $totalCount = $query->count();

            // Paginate
            $payments = $query->orderBy('created_at', 'desc')
                ->skip(($page - 1) * $limit)
                ->take($limit)
                ->get();

            // Calculate statistics
            $stats = [
                'total_payments' => PartnerPayment::where('partner_id', $partnerId ?? $userId)->count(),
                'pending_payments' => PartnerPayment::where('partner_id', $partnerId ?? $userId)
                    ->whereNull('validated_at')->count(),
                'validated_payments' => PartnerPayment::where('partner_id', $partnerId ?? $userId)
                    ->whereNotNull('validated_at')->count(),
                'total_amount' => (float)PartnerPayment::where('partner_id', $partnerId ?? $userId)
                    ->whereNotNull('validated_at')->sum('amount'),
                'pending_amount' => (float)PartnerPayment::where('partner_id', $partnerId ?? $userId)
                    ->whereNull('validated_at')->sum('amount'),
            ];

            Log::info(self::LOG_PREFIX . 'Partner payments retrieved successfully', [
                'user_id' => $userId,
                'count' => $payments->count()
            ]);

            return response()->json([
                'status' => 'Success',
                'message' => 'Partner payments retrieved successfully',
                'data' => [
                    'payments' => $payments,
                    'statistics' => $stats,
                    'pagination' => [
                        'current_page' => $page,
                        'per_page' => $limit,
                        'total' => $totalCount,
                        'total_pages' => ceil($totalCount / $limit),
                    ]
                ]
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            Log::error(self::LOG_PREFIX . 'Error retrieving partner payments', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'Failed',
                'message' => 'Failed to retrieve partner payments',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get a single partner payment by ID
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function show(Request $request, int $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer|exists:users,id',
        ]);

        if ($validator->fails()) {
            Log::error(self::LOG_PREFIX . 'Validation failed for show', ['errors' => $validator->errors()]);
            return response()->json([
                'status' => 'Failed',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $userId = intval($request->input('user_id'));

            // Verify user is a platform partner
            $isPartner = $this->verifyUserIsPartner($userId);
            if (!$isPartner) {
                return response()->json([
                    'status' => 'Failed',
                    'message' => 'User is not a platform partner'
                ], Response::HTTP_FORBIDDEN);
            }

            $payment = $this->partnerPaymentService->getById($id);
            // Verify user has access to this payment (is the partner)
            if ($payment->partner_id !== $userId) {
                return response()->json([
                    'status' => 'Failed',
                    'message' => 'Unauthorized access to this payment'
                ], Response::HTTP_FORBIDDEN);
            }

            Log::info(self::LOG_PREFIX . 'Partner payment retrieved successfully', [
                'payment_id' => $id,
                'user_id' => $userId
            ]);

            return response()->json([
                'status' => 'Success',
                'message' => 'Partner payment retrieved successfully',
                'data' => $payment
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            Log::error(self::LOG_PREFIX . 'Error retrieving partner payment', [
                'payment_id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'status' => 'Failed',
                'message' => 'Partner payment not found',
                'error' => $e->getMessage()
            ], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Create a demand (financial request) for a partner payment
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function createDemand(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer|exists:users,id',
            'amount' => 'required|numeric|min:0',
            'payment_ids' => 'nullable|array',
            'payment_ids.*' => 'integer|exists:partner_payments,id',
            'note' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            Log::error(self::LOG_PREFIX . 'Validation failed for createDemand', ['errors' => $validator->errors()]);
            return response()->json([
                'status' => 'Failed',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $userId = $request->input('user_id');
            $amount = $request->input('amount');
            $paymentIds = $request->input('payment_ids', []);
            $note = $request->input('note');

            // Verify user is a platform partner
            $isPartner = $this->verifyUserIsPartner($userId);
            if (!$isPartner) {
                return response()->json([
                    'status' => 'Failed',
                    'message' => 'User is not a platform partner'
                ], Response::HTTP_FORBIDDEN);
            }

            // If payment_ids provided, verify they belong to this partner
            if (!empty($paymentIds)) {
                $invalidPayments = PartnerPayment::whereIn('id', $paymentIds)
                    ->where('partner_id', '!=', $userId)
                    ->count();

                if ($invalidPayments > 0) {
                    return response()->json([
                        'status' => 'Failed',
                        'message' => 'Some payment IDs do not belong to this partner'
                    ], Response::HTTP_FORBIDDEN);
                }

                // Calculate total from payments
                $paymentsTotal = PartnerPayment::whereIn('id', $paymentIds)
                    ->whereNotNull('validated_at')
                    ->sum('amount');

                // Verify amount matches payments total
                if ($paymentsTotal != $amount) {
                    return response()->json([
                        'status' => 'Failed',
                        'message' => 'Amount does not match total of selected payments',
                        'details' => [
                            'requested_amount' => $amount,
                            'payments_total' => $paymentsTotal
                        ]
                    ], Response::HTTP_BAD_REQUEST);
                }
            }

            DB::beginTransaction();

            // Create financial request (demand)
            $demand = FinancialRequest::create([
                'idSender' => $userId,
                'date' => now(),
                'amount' => $amount,
                'status' => 0, // Pending
                'typeReq' => 'partner_payment',
                'securityCode' => $this->generateSecurityCode(),
                'vu' => 0,
                'created_by' => $userId,
            ]);



            DB::commit();

            Log::info(self::LOG_PREFIX . 'Demand created successfully', [
                'user_id' => $userId,
                'amount' => $amount,
                'payment_ids' => $paymentIds
            ]);

            return response()->json([
                'status' => 'Success',
                'message' => 'Demand created successfully',
                'data' => [
                    'amount' => $demand->amount,
                    'status' => $demand->status,
                    'date' => $demand->date,
                    'security_code' => $demand->securityCode,
                    'linked_payments' => count($paymentIds)
                ]
            ], Response::HTTP_CREATED);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error(self::LOG_PREFIX . 'Error creating demand', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'Failed',
                'message' => 'Failed to create demand',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get payment statistics for a partner
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function statistics(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer|exists:users,id',
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date|after_or_equal:from_date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $userId = $request->input('user_id');
            $fromDate = $request->input('from_date');
            $toDate = $request->input('to_date');

            // Verify user is a platform partner
            $isPartner = $this->verifyUserIsPartner($userId);
            if (!$isPartner) {
                return response()->json([
                    'status' => 'Failed',
                    'message' => 'User is not a platform partner'
                ], Response::HTTP_FORBIDDEN);
            }

            $query = PartnerPayment::where('partner_id', $userId);

            if ($fromDate) {
                $query->where('payment_date', '>=', $fromDate);
            }
            if ($toDate) {
                $query->where('payment_date', '<=', $toDate);
            }

            $stats = [
                'total_payments' => $query->count(),
                'pending_payments' => (clone $query)->whereNull('validated_at')->count(),
                'validated_payments' => (clone $query)->whereNotNull('validated_at')->count(),
                'total_amount' => (float)(clone $query)->whereNotNull('validated_at')->sum('amount'),
                'pending_amount' => (float)(clone $query)->whereNull('validated_at')->sum('amount'),
                'payment_methods' => (clone $query)->select('method', DB::raw('count(*) as count'))
                    ->groupBy('method')
                    ->get(),
                'monthly_totals' => (clone $query)->whereNotNull('validated_at')
                    ->select(
                        DB::raw('DATE_FORMAT(payment_date, "%Y-%m") as month'),
                        DB::raw('SUM(amount) as total'),
                        DB::raw('COUNT(*) as count')
                    )
                    ->groupBy('month')
                    ->orderBy('month', 'desc')
                    ->limit(12)
                    ->get(),
            ];

            return response()->json([
                'status' => 'Success',
                'message' => 'Statistics retrieved successfully',
                'data' => $stats
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            Log::error(self::LOG_PREFIX . 'Error retrieving statistics', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'status' => 'Failed',
                'message' => 'Failed to retrieve statistics',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Verify if user is a platform partner
     *
     * @param int $userId
     * @return bool
     */
    private function verifyUserIsPartner(int $userId): bool
    {
        return DB::table('platforms')
            ->where(function ($query) use ($userId) {
                $query->where('financial_manager_id', $userId)
                    ->orWhere('marketing_manager_id', $userId)
                    ->orWhere('owner_id', $userId);
            })
            ->exists();
    }

    /**
     * Generate a security code for financial request
     *
     * @return string
     */
    private function generateSecurityCode(): string
    {
        return strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 8));
    }
}

