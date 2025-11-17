<?php

namespace App\Livewire;

use App\Models\Coupon;
use Core\Enum\CouponStatusEnum;
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
            Coupon::findOrFail($id)->update([
                'consumed' => 1,
                'consumption_date' => now(),
            ]);
            session()->flash('success', Lang::get('Coupon consumed Successfully'));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            session()->flash('danger', $exception->getMessage());
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
        $coupons = Coupon::where('user_id', auth()->user()->id)
            ->where('status', CouponStatusEnum::purchased->value)
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('pin', 'like', '%' . $this->search . '%')
                        ->orWhere('sn', 'like', '%' . $this->search . '%')
                        ->orWhere('value', 'like', '%' . $this->search . '%')
                        ->orWhereHas('platform', function ($query) {
                            $query->where('name', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->orderBy('id', 'desc')
            ->paginate($this->pageCount);

        return view('livewire.coupon-history', [
            'coupons' => $coupons
        ])->extends('layouts.master')->section('content');
    }
}
