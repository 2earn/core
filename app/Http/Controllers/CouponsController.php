<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\Request as Req;
use Illuminate\Support\Facades\Log;

class CouponsController extends Controller
{

    public function index()
    {
        return datatables(Coupon::orderBy('id', 'desc')->get())
            ->addColumn('action', function ($coupon) {
                return view('parts.datatable.coupon-action', ['coupon' => $coupon]);
            })
            ->addColumn('platform_id', function ($coupon) {
                if ($coupon->platform()->first()) {
                    return $coupon->platform()->first()->id . ' - ' . $coupon->platform()->first()->name;
                }
                return '**';
            })
            ->addColumn('value', function ($coupon) {
                return view('parts.datatable.coupon-value', ['coupon' => $coupon]);
            })
            ->addColumn('consumed', function ($coupon) {
                return view('parts.datatable.coupon-consumed', ['coupon' => $coupon]);
            })
            ->addColumn('dates', function ($coupon) {
                return view('parts.datatable.coupon-dates', ['coupon' => $coupon]);
            })
            ->rawColumns(['action', 'platform_id'])
            ->make(true);
    }
    public function deleteCoupon(Req $request)
    {
        $ids = $request->input('ids');

        if (empty($ids)) {
            return response()->json(['message' => 'No IDs provided'], 400);
        }

        try {
            Coupon::whereIn('id', $ids)->where('consumed', 0)->delete();
            return response()->json(['message' => 'Coupons deleted successfully (Only not consumed)']);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return response()->json(['message' => 'An error occurred while deleting the coupons'], 500);
        }
    }

}
