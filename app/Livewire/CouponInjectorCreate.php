<?php

namespace App\Livewire;

use App\Models\BalanceInjectorCoupon;
use Core\Enum\BalanceEnum;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Str;
use Livewire\Component;

class CouponInjectorCreate extends Component
{
    const INDEX_ROUTE_NAME = 'coupon_injector_index';

    public $attachment_date;
    public $numberOfCoupons;
    public $value;
    public $type;
    public $category_id;
    public $allCategories;

    protected $rules = [
        'numberOfCoupons' => 'required|numeric|min:1|max:100',
        'category_id' => 'required',
        'attachment_date' => ['required', 'after_or_equal:today'],
    ];

    public function mount()
    {
        $this->allCategories = [
            BalanceEnum::CASH, BalanceEnum::BFS, BalanceEnum::DB
        ];
        $this->category_id = BalanceEnum::CASH->value;
    }

    public function store()
    {

        $this->validate();
        try {
            if (!is_numeric($this->numberOfCoupons) || $this->numberOfCoupons <= 0 || $this->numberOfCoupons >= 100) {
                throw new \Exception('Number of coupons must be a positive number less than 100');
            }

            $coupon = [
                'attachment_date' => $this->attachment_date,
                'value' => $this->value,
                'category' => intval($this->category_id),
                'consumed' => false
            ];

            for ($i = 1; $i <= $this->numberOfCoupons; $i++) {
                $coupon['pin'] = strtoupper(Str::random(20));
                $coupon['sn'] = 'SN' . rand(100000, 999999);
                $coupon['type'] = $this->type;
                BalanceInjectorCoupon::create($coupon);
            }
            return redirect()->route(self::INDEX_ROUTE_NAME, ['locale' => app()->getLocale()])->with('success', Lang::get('Coupons created Successfully'));
        } catch (\Exception $exception) {
            return redirect()->route('coupon_injector_create', ['locale' => app()->getLocale()])->with('danger', Lang::get('Coupons creation Failed') . ' ' . $exception->getMessage());
        }
    }

    public function cancel()
    {
        return redirect()->route('coupon_injector_index', ['locale' => app()->getLocale()])->with('warning', Lang::get('Coupons operation cancelled'));
    }

    public function render()
    {
        return view('livewire.coupon-injector-create')->extends('layouts.master')->section('content');
    }
}
