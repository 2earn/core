<?php

namespace App\Http\Controllers\Api\partner;

use App\Http\Controllers\Controller;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class OrderDetailsPartnerController extends Controller
{
    private const LOG_PREFIX = '[OrderDetailsPartnerController] ';

    public function __construct()
    {
        $this->middleware('check.url');
    }

    public function store(Request $request)
    {
        // Debug logging to see what's being received
        $allData = $request->all();
        Log::info(self::LOG_PREFIX . 'Store request received', [
            'all' => $allData,
            'input' => $request->input(),
            'has_json' => $request->isJson(),
            'content_type' => $request->header('Content-Type'),
            'method' => $request->method()
        ]);

        // Check for unsubstituted Postman variables (literal {{...}} strings)
        $unsubstitutedVars = [];
        foreach ($allData as $key => $value) {
            if (is_string($value) && preg_match('/^\{\{.*\}\}$/', $value)) {
                $unsubstitutedVars[$key] = $value;
            }
        }

        if (!empty($unsubstitutedVars)) {
            Log::warning(self::LOG_PREFIX . 'Postman variables not substituted', ['variables' => $unsubstitutedVars]);
            return response()->json([
                'status' => 'Failed',
                'message' => 'Postman variables are not properly substituted. Please ensure all environment variables are set.',
                'warning' => 'The following fields contain unsubstituted Postman variables: ' . implode(', ', array_keys($unsubstitutedVars)),
                'unsubstituted_vars' => $unsubstitutedVars
            ], Response::HTTP_BAD_REQUEST);
        }

        $validator = Validator::make($allData, [
            'order_id' => 'required|numeric|exists:orders,id',
            'item_id' => 'required|numeric|exists:items,id',
            'qty' => 'required|numeric|min:1',
            'unit_price' => 'required|numeric|min:0',
            'shipping' => 'nullable|numeric|min:0',
            'created_by' => 'required|numeric|exists:users,id',
        ]);

        if ($validator->fails()) {
            Log::error(self::LOG_PREFIX . 'Validation failed', ['errors' => $validator->errors()]);
            return response()->json([
                'status' => 'Failed',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $data = $request->all();
        $data['shipping'] = $data['shipping'] ?? 0;
        $data['total_amount'] = ($data['qty'] * $data['unit_price']) + $data['shipping'];

        $orderDetail = OrderDetail::create($data);

        Log::info(self::LOG_PREFIX . 'Order detail created successfully', ['id' => $orderDetail->id]);
        return response()->json([
            'status' => 'Success',
            'message' => 'Order detail created successfully',
            'data' => $orderDetail
        ], Response::HTTP_CREATED);
    }

    public function update(Request $request, $orderDetailId)
    {
        $orderDetail = OrderDetail::find($orderDetailId);
        if (!$orderDetail) {
            Log::error(self::LOG_PREFIX . 'Order detail not found', ['id' => $orderDetailId]);
            return response()->json([
                'status' => 'Failed',
                'message' => 'Order detail not found'
            ], Response::HTTP_NOT_FOUND);
        }

        $allData = $request->all();

        // Check for unsubstituted Postman variables
        $unsubstitutedVars = [];
        foreach ($allData as $key => $value) {
            if (is_string($value) && preg_match('/^\{\{.*\}\}$/', $value)) {
                $unsubstitutedVars[$key] = $value;
            }
        }

        if (!empty($unsubstitutedVars)) {
            Log::warning(self::LOG_PREFIX . 'Postman variables not substituted', ['variables' => $unsubstitutedVars]);
            return response()->json([
                'status' => 'Failed',
                'message' => 'Postman variables are not properly substituted.',
                'unsubstituted_vars' => $unsubstitutedVars
            ], Response::HTTP_BAD_REQUEST);
        }

        $validator = Validator::make($allData, [
            'qty' => 'sometimes|numeric|min:1',
            'unit_price' => 'sometimes|numeric|min:0',
            'shipping' => 'sometimes|numeric|min:0',
            'note' => 'nullable|string',
            'updated_by' => 'required|numeric|exists:users,id',
        ]);

        if ($validator->fails()) {
            Log::error(self::LOG_PREFIX . 'Validation failed', ['errors' => $validator->errors()]);
            return response()->json([
                'status' => 'Failed',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $data = $allData;

        if (isset($data['qty']) || isset($data['unit_price']) || isset($data['shipping'])) {
            $qty = $data['qty'] ?? $orderDetail->qty;
            $unitPrice = $data['unit_price'] ?? $orderDetail->unit_price;
            $shipping = $data['shipping'] ?? $orderDetail->shipping;

            $data['total_amount'] = ($qty * $unitPrice) + $shipping;
        }

        $orderDetail->update($data);

        Log::info(self::LOG_PREFIX . 'Order detail updated successfully', ['id' => $orderDetailId]);
        return response()->json([
            'status' => 'Success',
            'message' => 'Order detail updated successfully',
            'data' => $orderDetail
        ], Response::HTTP_OK);
    }
}
