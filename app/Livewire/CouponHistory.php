<?php

namespace App\Livewire;

use App\Services\Coupon\CouponService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;

class CouponHistory extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public ?string $search = "";
    public ?string $pageCount = "10";

    public $listeners = ['markAsConsumed' => 'markAsConsumed', 'verifPassword' => 'verifPassword'];

    protected function queryString()
    {
        return [
            'search' => [
                'as' => 'q',
            ],
            'pageCount' => [
                'as' => 'pc',
            ],
        ];
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPageCount()
    {
        $this->resetPage();
    }

    public function markAsConsumed($id)
    {
        try {
            $couponService = app(CouponService::class);
            $couponService->markAsConsumed($id);
            session()->flash('success', Lang::get('Coupon consumed Successfully'));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            session()->flash('danger', $exception->getMessage());
        }
    }

    public function verifPassword($password, $sn)
    {
        if (Hash::check($password, auth()->user()->password)) {
            $couponService = app(CouponService::class);
            $coupon = $couponService->getBySn($sn);
            $this->dispatch('showPin', ['title' => trans('This is the pin code'), 'html' => '<input class="form-control input-sm" value="' . $coupon->pin . '">']);
        } else {
            $this->dispatch('cancelPin', ['title' => trans('Valid code'), 'text' => trans('Invalid code')]);
        }
    }

    public function render()
    {
        $couponService = app(CouponService::class);
        $coupons = $couponService->getPurchasedCouponsPaginated(
            auth()->user()->id,
            $this->search,
            (int)$this->pageCount
        );

        return view('livewire.coupon-history', [
            'coupons' => $coupons
        ])->extends('layouts.master')->section('content');
    }
}
