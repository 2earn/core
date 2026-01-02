<?php

namespace App\Livewire;

use App\Services\Coupon\CouponService;
use Illuminate\Support\Facades\Lang;
use Livewire\Component;
use Livewire\WithPagination;

class CouponIndex extends Component
{
    use WithPagination;

    protected CouponService $couponService;
    protected $paginationTheme = 'bootstrap';

    public ?string $search = "";
    public ?string $pageCount = "10";
    public array $selectedIds = [];
    public bool $selectAll = false;

    public $listeners = ['delete' => 'delete'];

    public function boot(CouponService $couponService)
    {
        $this->couponService = $couponService;
    }

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
        $this->selectedIds = [];
        $this->selectAll = false;
    }

    public function updatingPageCount()
    {
        $this->resetPage();
        $this->selectedIds = [];
        $this->selectAll = false;
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $coupons = $this->couponService->getCouponsPaginated(
                $this->search,
                'id',
                'desc',
                (int)$this->pageCount
            );
            $this->selectedIds = $coupons->getCollection()->pluck('id')->map(fn($id) => (string)$id)->toArray();
        } else {
            $this->selectedIds = [];
        }
    }

    public function delete($id)
    {
        try {
            $this->couponService->deleteById($id);
            session()->flash('success', Lang::get('Coupon deleted successfully'));
            $this->selectedIds = array_diff($this->selectedIds, [$id]);
        } catch (\Exception $exception) {
            session()->flash('danger', $exception->getMessage());
        }
    }

    public function deleteSelected()
    {
        try {
            if (empty($this->selectedIds)) {
                session()->flash('warning', Lang::get('No coupons selected'));
                return;
            }

            $deleted = $this->couponService->deleteMultipleByIds($this->selectedIds);

            $this->selectedIds = [];
            $this->selectAll = false;

            session()->flash('success', Lang::get('Coupons deleted successfully') . ' (' . Lang::get('Only not consumed') . ')');
        } catch (\Exception $exception) {
            session()->flash('danger', $exception->getMessage());
        }
    }

    public function render()
    {
        $coupons = $this->couponService->getCouponsPaginated(
            $this->search,
            'id',
            'desc',
            (int)$this->pageCount
        );

        return view('livewire.coupon-index', [
            'coupons' => $coupons
        ])->extends('layouts.master')->section('content');
    }
}
