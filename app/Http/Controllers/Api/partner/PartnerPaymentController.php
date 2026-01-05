<?php

namespace App\Http\Controllers\Api\partner;

use App\Http\Controllers\Controller;
use App\Models\PartnerPayment;
use App\Services\PartnerPayment\PartnerPaymentService;
use App\Models\FinancialRequest;
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

    public function index(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'partner_id' => 'required|integer|exists:users,id',
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
            $partnerId = $request->input('partner_id');
            $status = $request->input('status', 'all');
            $method = $request->input('method');
            $fromDate = $request->input('from_date');
            $toDate = $request->input('to_date');
            $page = $request->input('page', 1);
            $limit = $request->input('limit', 15);

            $isPartner = $this->verifyUserIsPartner($partnerId);
            if (!$isPartner) {
                return response()->json([
                    'status' => 'Failed',
                    'message' => 'User is not a platform partner'
                ], Response::HTTP_FORBIDDEN);
            }

            $query = PartnerPayment::with(['partner', 'validator']);

            if ($partnerId) {
                $query->where('partner_id', $partnerId);
            }

            if ($status === 'pending') {
                $query->whereNull('validated_at');
            } elseif ($status === 'validated') {
                $query->whereNotNull('validated_at');
            }

            if ($method) {
                $query->where('method', $method);
            }

            if ($fromDate) {
                $query->where('payment_date', '>=', $fromDate);
            }
            if ($toDate) {
                $query->where('payment_date', '<=', $toDate);
            }

            $totalCount = $query->count();

            $payments = $query->orderBy('created_at', 'desc')
                ->skip(($page - 1) * $limit)
                ->take($limit)
                ->get();

            $stats = [
                'total_payments' => PartnerPayment::where('partner_id', $partnerId )->count(),
                'pending_payments' => PartnerPayment::where('partner_id', $partnerId)
                    ->whereNull('validated_at')->count(),
                'validated_payments' => PartnerPayment::where('partner_id', $partnerId)
                    ->whereNotNull('validated_at')->count(),
                'total_amount' => (float)PartnerPayment::where('partner_id', $partnerId)
                    ->whereNotNull('validated_at')->sum('amount'),
                'pending_amount' => (float)PartnerPayment::where('partner_id', $partnerId)
                    ->whereNull('validated_at')->sum('amount'),
            ];

            Log::info(self::LOG_PREFIX . 'Partner payments retrieved successfully', [
                'partner_id' => $partnerId,
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

    public function show(Request $request, int $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'partner_id' => 'required|integer|exists:users,id',
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
            $partnerId = intval($request->input('partner_id'));

            $isPartner = $this->verifyUserIsPartner($partnerId);
            if (!$isPartner) {
                return response()->json([
                    'status' => 'Failed',
                    'message' => 'User is not a platform partner'
                ], Response::HTTP_FORBIDDEN);
            }

            $payment = $this->partnerPaymentService->getById($id);
            if ($payment->partner_id !== $partnerId) {
                return response()->json([
                    'status' => 'Failed',
                    'message' => 'Unauthorized access to this payment'
                ], Response::HTTP_FORBIDDEN);
            }

            Log::info(self::LOG_PREFIX . 'Partner payment retrieved successfully', [
                'payment_id' => $id,
                'user_id' => $partnerId
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

    public function createDemand(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'partner_id' => 'required|integer|exists:users,id',
            'amount' => 'required|numeric|min:0',
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
            $partnerId = $request->input('partner_id');
            $amount = $request->input('amount');
            $note = $request->input('note');

            $isPartner = $this->verifyUserIsPartner($partnerId);
            if (!$isPartner) {
                return response()->json([
                    'status' => 'Failed',
                    'message' => 'User is not a platform partner'
                ], Response::HTTP_FORBIDDEN);
            }

            DB::beginTransaction();

            $partnerPayment = PartnerPayment::create([
                'amount' => $amount,
                'method' => 'demand_request',
                'payment_date' => now(),
                'partner_id' => $partnerId, // Same user (partner requesting their own payment)
                'created_by' => $partnerId,
            ]);

            DB::commit();

            Log::info(self::LOG_PREFIX . 'Demand created successfully', [
                'partner_payment_id' => $partnerPayment->id,
                'partner_id' => $partnerId,
                'amount' => $amount,
            ]);

            return response()->json([
                'status' => 'Success',
                'message' => 'Demand created successfully',
                'data' => [
                    $partnerPayment
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

    public function statistics(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'partner_id' => 'required|integer|exists:users,id',
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
            $partnerId = $request->input('partner_id');
            $fromDate = $request->input('from_date');
            $toDate = $request->input('to_date');

            $isPartner = $this->verifyUserIsPartner($partnerId);
            if (!$isPartner) {
                return response()->json([
                    'status' => 'Failed',
                    'message' => 'User is not a platform partner'
                ], Response::HTTP_FORBIDDEN);
            }

            $query = PartnerPayment::where('partner_id', $partnerId);

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

    private function generateSecurityCode(): string
    {
        return strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 8));
    }
}

