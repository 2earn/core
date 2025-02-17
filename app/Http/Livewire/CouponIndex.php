<?php

namespace App\Http\Livewire;

use App\Models\Coupon;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class CouponIndex extends Component
{

    public $listeners = ['delete' => 'delete'];

    public function delete($id)
    {
        try {
            Coupon::findOrFail($id)->delete();
            return redirect()->route('coupon_index', ['locale' => app()->getLocale()])->with('success', Lang::get('Platform Deleted Successfully'));
        }catch (\Exception $exception){
            Log::error($exception->getMessage());
            return redirect()->route('coupon_index', ['locale' => app()->getLocale()])->with('danger', $exception->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.coupon-index')->extends('layouts.master')->section('content');
    }
}
