<?php

namespace App\Livewire;

use App\Models\Coupon;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;

class CouponIndex extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public ?string $search = "";
    public ?string $pageCount = "10";
    public array $selectedIds = [];
    public bool $selectAll = false;

    public $listeners = ['delete' => 'delete'];

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
            $this->selectedIds = $this->getCoupons()->pluck('id')->map(fn($id) => (string)$id)->toArray();
        } else {
            $this->selectedIds = [];
        }
    }

    public function delete($id)
    {
        try {
            Coupon::findOrFail($id)->delete();
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
                session()->flash('warning', Lang::get('No coupons selected'));
                return;
            }

            $deleted = Coupon::whereIn('id', $this->selectedIds)
                ->where('consumed', 0)
                ->delete();

            $this->selectedIds = [];
            $this->selectAll = false;

            session()->flash('success', Lang::get('Coupons deleted successfully') . ' (' . Lang::get('Only not consumed') . ')');
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            session()->flash('danger', $exception->getMessage());
        }
    }

    private function getCoupons()
    {
        return Coupon::when($this->search, function ($query) {
            $query->where(function ($q) {
                $q->where('pin', 'like', '%' . $this->search . '%')
                    ->orWhere('sn', 'like', '%' . $this->search . '%')
                    ->orWhere('value', 'like', '%' . $this->search . '%')
                    ->orWhereHas('platform', function ($query) {
                        $query->where('name', 'like', '%' . $this->search . '%');
                    });
            });
        })->orderBy('id', 'desc');
    }

    public function render()
    {
        $coupons = $this->getCoupons()->paginate($this->pageCount);

        return view('livewire.coupon-index', [
            'coupons' => $coupons
        ])->extends('layouts.master')->section('content');
    }
}
