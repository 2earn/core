<?php

namespace App\Http\Controllers;

use App\Models\Coupon;

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

}
