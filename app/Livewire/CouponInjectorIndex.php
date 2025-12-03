<?php

namespace App\Livewire;

use App\Models\BalanceInjectorCoupon;
use App\Services\Coupon\BalanceInjectorCouponService;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;

class CouponInjectorIndex extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $selectedIds = [];
    public $selectAll = false;

    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    protected $queryString = ['search', 'sortField', 'sortDirection'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedIds = $this->coupons->pluck('id')->map(fn($id) => (string) $id)->toArray();
        } else {
            $this->selectedIds = [];
        }
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function delete($id)
    {
        try {
            $couponService = app(BalanceInjectorCouponService::class);
            $couponService->delete($id);
            session()->flash('success', Lang::get('Coupon deleted successfully'));
            $this->selectedIds = array_diff($this->selectedIds, [$id]);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            session()->flash('danger', $exception->getMessage());
        }
    }

    public function deleteSelected()
    {
        try {
            if (empty($this->selectedIds)) {
                session()->flash('warning', Lang::get('No rows selected'));
                return;
            }

            $couponService = app(BalanceInjectorCouponService::class);
            $couponService->deleteMultiple($this->selectedIds);

            session()->flash('success', Lang::get('Coupons deleted successfully (Only not consumed)'));
            $this->selectedIds = [];
            $this->selectAll = false;
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            session()->flash('danger', $exception->getMessage());
        }
    }

    public function getCouponsProperty()
    {
        $couponService = app(BalanceInjectorCouponService::class);
        return $couponService->getPaginated(
            $this->search,
            $this->sortField,
            $this->sortDirection,
            10
        );
    }

    public function render()
    {
        return view('livewire.coupon-injector-index', [
            'coupons' => $this->coupons
        ])->extends('layouts.master')->section('content');
    }
}
