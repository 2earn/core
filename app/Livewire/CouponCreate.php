<?php

namespace App\Livewire;


use App\Models\Coupon;
use Livewire\Component;
use Core\Models\Platform;
use Illuminate\Support\Facades\Lang;

class CouponCreate extends Component
{
    const INDEX_ROUTE_NAME = 'coupon_index';

    public $pins;
    public $sn;
    public $attachment_date;
    public $value;
    public $platform_id;
    public $selectPlatforms;

    protected $rules = [
        'pins' => 'required',
        'sn' => 'required',
        'platform_id' => 'required',
        'attachment_date' => ['required', 'after_or_equal:today'],
    ];

    public function mount()
    {
        $platforms = Platform::all();
        $selectPlatforms = [];
        foreach ($platforms as $platform) {
            $selectPlatforms[] = ['name' => $platform->name, 'value' => $platform->id];
            $this->platform_id = $platform->id;
        }
        $this->selectPlatforms = $selectPlatforms;
    }

    public function store()
    {

        $this->validate();
        try {
            $coupon = [
                'attachment_date' => $this->attachment_date,
                'value' => $this->value,
                'platform_id' => $this->platform_id,
                'consumed' => false
            ];

            $pins = explode(',', $this->pins);
            $sns = explode(',', $this->sn);
            foreach ($pins as $key => $pin) {
                $coupon['pin'] = $pin;
                $coupon['sn'] = $sns[$key];
                Coupon::create($coupon);
            }
            return redirect()->route(self::INDEX_ROUTE_NAME, ['locale' => app()->getLocale()])->with('success', Lang::get('Coupons created Successfully'));
        } catch (\Exception $exception) {
            return redirect()->route('coupon_create', ['locale' => app()->getLocale()])->with('danger', Lang::get('Coupons creation Failed') . ' ' . $exception->getMessage());
        }
    }

    public function cancel()
    {
        return redirect()->route('coupon_index', ['locale' => app()->getLocale()])->with('warning', Lang::get('Coupons operation cancelled'));
    }

    public function render()
    {


        return view('livewire.coupon-create')->extends('layouts.master')->section('content');
    }
}
