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

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|exists:orders,id',
            'item_id' => 'required|exists:items,id',
            'qty' => 'required|numeric|min:1',
            'unit_price' => 'required|numeric|min:0',
            'shipping' => 'nullable|numeric|min:0',
            'created_by' => 'required|exists:users,id',
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

        return response()->json($orderDetail, Response::HTTP_CREATED);
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

        $validator = Validator::make($request->all(), [
            'qty' => 'sometimes|numeric|min:1',
            'unit_price' => 'sometimes|numeric|min:0',
            'shipping' => 'sometimes|numeric|min:0',
            'note' => 'nullable|string',
            'updated_by' => 'required|exists:users,id',
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
