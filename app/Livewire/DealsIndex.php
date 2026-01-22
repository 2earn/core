<?php

namespace App\Livewire;

use App\Models\Deal;
use App\Models\User;
use App\Services\Deals\DealService;
use App\Services\Platform\PlatformService;
use App\Enums\DealStatus;
use App\Enums\DealTypeEnum;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Livewire\Component;
use Livewire\WithPagination;

class DealsIndex extends Component
{
    use WithPagination;

    const INDEX_ROUTE_NAME = 'deals_index';
    const DATE_FORMAT = 'd/m/Y H:i:s';

    public $listeners = [
        'delete' => 'delete',
        'updateDeal' => 'updateDeal',
        'refreshDeals' => '$refresh'
    ];
    public $allPlatforms, $allTypes, $allStatuses, $currentRouteName;

    public $keyword = '';
    public $selectedStatuses = [];
    public $selectedTypes = [];
    public $selectedPlatforms = [];
    public $startDateFrom = null;
    public $startDateTo = null;
    public $endDateFrom = null;
    public $endDateTo = null;
    public $perPage = 5;

    protected DealService $dealService;
    protected PlatformService $platformService;

    public function boot(DealService $dealService, PlatformService $platformService)
    {
        $this->dealService = $dealService;
        $this->platformService = $platformService;
    }

    public function mount()
    {
        $this->currentRouteName = Route::currentRouteName();

        if (User::isSuperAdmin()) {
            $this->allPlatforms = $this->platformService->getEnabledPlatforms();
        } else {
            $this->allPlatforms = $this->platformService->getPlatformsManagedByUser(
                auth()->user()->id,
                false
            );
        }

        $this->allStatuses = DealStatus::cases();
        $this->allTypes = DealTypeEnum::cases();
    }

    public function updatingKeyword()
    {
        $this->resetPage();
    }

    public function updatingSelectedStatuses()
    {
        $this->resetPage();
    }

    public function updatingSelectedTypes()
    {
        $this->resetPage();
    }

    public function updatingSelectedPlatforms()
    {
        $this->resetPage();
    }

    public function updatingStartDateFrom()
    {
        $this->resetPage();
    }

    public function updatingStartDateTo()
    {
        $this->resetPage();
    }

    public function updatingEndDateFrom()
    {
        $this->resetPage();
    }

    public function updatingEndDateTo()
    {
        $this->resetPage();
    }


    public function updateDeal($id, $status)
    {
        match (intval($status)) {
            0 => Deal::validateDeal($id),
            2 => Deal::open($id),
            3 => Deal::close($id),
            4 => Deal::archive($id),
        };
    }

    public static function delete($id)
    {
        try {
            Deal::findOrFail($id)->delete();
            return redirect()->route(self::INDEX_ROUTE_NAME, ['locale' => app()->getLocale()])->with('success', Lang::get('Deal Deleted Successfully'));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->route(self::INDEX_ROUTE_NAME, ['locale' => app()->getLocale()])->with('warning', Lang::get('This Deal cant be Deleted !') . " " . $exception->getMessage());
        }
    }

    public function render()
    {
        $choosenDeals = $this->dealService->getFilteredDeals(
            User::isSuperAdmin(),
            auth()->user()->id,
            $this->keyword,
            $this->selectedStatuses,
            $this->selectedTypes,
            $this->selectedPlatforms,
            $this->startDateFrom,
            $this->startDateTo,
            $this->endDateFrom,
            $this->endDateTo,
            $this->perPage
        );

        return view('livewire.deals-index', [
            'choosenDeals' => $choosenDeals
        ])->extends('layouts.master')->section('content');
    }
}
