<?php

namespace App\Livewire;

use App\Services\Coupon\CouponService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;

class CouponInjectorUserIndex extends Component
{
    use WithPagination;

    const PAGE_SIZE = 5;
    public $search = '';
    public $selectedCoupons = [];
    public $selectAll = false;
    protected $paginationTheme = 'bootstrap';
    public $listeners = ['deleteCoupon' => 'deleteCoupon', 'deleteSelected' => 'deleteSelected'];

    protected CouponService $couponService;

    public function boot(CouponService $couponService)
    {
        $this->couponService = $couponService;
    }

    public function resetPage($pageName = 'page')
    {
        $this->setPage(1, $pageName);
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $coupons = $this->couponService
                ->getUserCouponsPaginated(Auth::id(), $this->search, self::PAGE_SIZE);
            $this->selectedCoupons = $coupons->items()
                ? collect($coupons->items())->pluck('id')->toArray()
                : [];
        } else {
            $this->selectedCoupons = [];
        }
    }

    public function deleteCoupon($id)
    {
        try {
            $this->couponService->delete($id, Auth::id());
            session()->flash('success', Lang::get('Coupon Deleted Successfully'));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            session()->flash('danger', Lang::get('Cannot delete consumed coupon'));
        }
    }

    public function deleteSelected()
    {
        try {
            if (empty($this->selectedCoupons)) {
                session()->flash('warning', Lang::get('No coupons selected'));
                return;
            }

            $deleted = $this->couponService->deleteMultiple($this->selectedCoupons, Auth::id());
            $this->selectedCoupons = [];
            $this->selectAll = false;
            session()->flash('success', Lang::get('Deleted :count coupon(s) successfully', ['count' => $deleted]));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            session()->flash('danger', Lang::get('An error occurred while deleting coupons'));
        }
    }

    public function render()
    {
        $coupons = $this->couponService->getUserCouponsPaginated(Auth::id(), $this->search, self::PAGE_SIZE);
        return view('livewire.coupon-injector-user-index', [
            'coupons' => $coupons
        ]);
    }
}
