<?php

namespace App\Http\Controllers;

use App\Models\BalanceInjectorCoupon;
use App\Models\Coupon;
use Core\Enum\CouponStatusEnum;

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

    public function userCoupons(){
        return datatables(Coupon::where('user_id', auth()->user()->id)->where('status', CouponStatusEnum::purchased->value)->orderBy('id', 'desc')->get())
            ->addColumn('action', function ($coupon) {
                return view('parts.datatable.coupon-consume', ['coupon' => $coupon]);
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
            ->addColumn('pin', function ($coupon) {
                return view('parts.datatable.coupon-pin', ['coupon' => $coupon]);
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
    public function user()
    {
        $coupons = BalanceInjectorCoupon::where('user_id', auth()->user()->id)->orderBy('created_at', 'desc')->get();
        return datatables($coupons)
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
