<?php

namespace App\Http\Livewire;

use App\Models\Coupon;
use Livewire\Component;
use Log;
use Lang;

class CouponHistory extends Component
{

    public $listeners = ['markAsConsumed' => 'markAsConsumed'];

    public function markAsConsumed($id)
    {
        try {
            Coupon::findOrFail($id)->update([
                'consumed' => 1,
                'consumption_date' => now(),
            ]);
            return redirect()->route('coupon_history', ['locale' => app()->getLocale()])->with('success', Lang::get('Coupon consumed Successfully'));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->route('coupon_history', ['locale' => app()->getLocale()])->with('danger', $exception->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.coupon-history')->extends('layouts.master')->section('content');
    }
}
