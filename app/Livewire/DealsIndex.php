<?php

namespace App\Livewire;

use App\Models\Deal;
use App\Models\User;
use Core\Enum\DealStatus;
use Core\Enum\DealTypeEnum;
use Core\Models\Platform;
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
        'refreshDeals' => 'filterDeals'
    ];
    public $allPlatforms, $allTypes, $allStatuses, $currentRouteName;

    public $keyword = '';
    public $selectedStatuses = [];
    public $selectedTypes = [];
    public $selectedPlatforms = [];
    public $choosenDeals = [];

    public function mount()
    {
        $this->currentRouteName = Route::currentRouteName();
        if (User::isSuperAdmin()) {
            $this->allPlatforms = Platform::all();
        } else {
            $this->allPlatforms = Platform::where(function ($query) {
                $query->where('financial_manager_id', '=', auth()->user()->id)
                    ->orWhere('marketing_manager_id', '=', auth()->user()->id)
                    ->orWhere('owner_id', '=', auth()->user()->id);
            })->get();
        }
        $this->allStatuses = DealStatus::cases();
        $this->allTypes = DealTypeEnum::cases();
        $this->filterDeals();
    }

    public function prepareQuery()
    {
        $query = Deal::query();

        if (User::isSuperAdmin()) {
            $query->whereNot('status', DealStatus::Archived->value);
        } else {
            $query->whereHas('platform', function ($query) {
                $query->where('financial_manager_id', '=', auth()->user()->id)
                    ->orWhere('owner_id', '=', auth()->user()->id)
                    ->orWhere('marketing_manager_id', '=', auth()->user()->id);
            });
        }


        if ($this->keyword) {
            $query->where('name', 'like', '%' . $this->keyword . '%');
        }
        if (!empty($this->selectedStatuses)) {
            $query->whereIn('status', $this->selectedStatuses);
        }

        if (!empty($this->selectedTypes)) {
            $query->whereIn('type', $this->selectedTypes);
        }

        if (!empty($this->selectedPlatforms)) {
            $query->whereIn('platform_id', $this->selectedPlatforms);
        }

        // Eager load relationships
        $query->with(['platform', 'pendingChangeRequest.requestedBy']);

        $query->orderBy('validated', 'ASC')->orderBy('platform_id', 'ASC');
        Log::info($query->toSql());
        Log::info($query->toRawSql());
        return $query->get();
    }

    public function filterDeals()
    {
        $this->choosenDeals = $this->prepareQuery();
        log::info(json_encode($this->choosenDeals));
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
        return view('livewire.deals-index')->extends('layouts.master')->section('content');
    }
}
