<?php

namespace App\Livewire;

use App\Models\Coupon;
use Illuminate\Support\Facades\Hash;
use Lang;
use Livewire\Component;
use Log;

class CouponHistory extends Component
{

    public $listeners = ['markAsConsumed' => 'markAsConsumed', 'verifPassword' => 'verifPassword'];

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

    public function verifPassword($password,$sn)
    {
        if (Hash::check($password, auth()->user()->password)) {
            $coupon= Coupon::where('sn',$sn)->first();
            $this->dispatch('showPin', ['title' => trans('This is the pin code'), 'html' => '<input class="form-control input-sm" value="'.$coupon->pin.'">']);
        } else {
            $this->dispatch('cancelPin', ['title' => trans('Valid code'), 'text' => trans('Invalid code')]);
        }
    }

    public function render()
    {
        return view('livewire.coupon-history')->extends('layouts.master')->section('content');
    }
}
