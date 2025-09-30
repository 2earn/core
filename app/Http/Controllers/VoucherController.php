<?php

namespace App\Http\Controllers;

use App\Models\BalanceInjectorCoupon;
use Illuminate\Http\Request;

class VoucherController extends Controller
{

    public function index()
    {
        return datatables(BalanceInjectorCoupon::orderBy('created_at', 'desc')->get())
            ->addColumn('action', function ($coupon) {
                return view('parts.datatable.coupon-action', ['coupon' => $coupon]);
            })
            ->addColumn('category', function ($coupon) {
                return view('parts.datatable.coupon-category', ['coupon' => $coupon]);
            })
            ->addColumn('value', function ($coupon) {
                return view('parts.datatable.coupon-value', ['coupon' => $coupon]);
            })
            ->addColumn('consumed', function ($coupon) {
                return view('parts.datatable.coupon-consumed', ['coupon' => $coupon]);
            })
            ->addColumn('dates', function ($coupon) {
                return view('parts.datatable.coupon-injector-dates', ['coupon' => $coupon]);
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
