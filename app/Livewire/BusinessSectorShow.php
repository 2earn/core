<?php

namespace App\Livewire;

use App\Models\BusinessSector;
use App\Services\Platform\PlatformService;
use Illuminate\Support\Facades\Lang;
use Livewire\Component;

class BusinessSectorShow extends Component
{
    public $items = [];
    protected $platformService;

    protected $listeners = [
        'deletebusinessSector' => 'deletebusinessSector'
    ];

    public function boot(PlatformService $platformService)
    {
        $this->platformService = $platformService;
    }

    public function mount($id)
    {
        if (!auth()->user()?->id == 384) {
            $this->redirect(route('home', ['locale' => app()->getLocale()]));
        }
        $this->idBusinessSector = $id;
    }

    public function deletebusinessSector($idBusinessSector)
    {
        BusinessSector::findOrFail($idBusinessSector)->delete();
        return redirect()->route('business_sector_index', ['locale' => app()->getLocale()])->with('success', Lang::get('Business sector Deleted Successfully'));
    }

    public function loadItems()
    {
        if (is_null($this->idBusinessSector)) {
            return [];
        }
        return $this->platformService->getItemsFromEnabledPlatforms($this->idBusinessSector);
    }

    public function render()
    {
        $businessSector = BusinessSector::with(['logoImage', 'thumbnailsImage', 'thumbnailsHomeImage'])
            ->find($this->idBusinessSector);

        if (is_null($businessSector)) {
            redirect()->route('business_sector_index', ['locale' => app()->getLocale()]);
        }

        // Use PlatformService to fetch platforms with active deals
        $platforms = $this->platformService->getPlatformsWithActiveDeals($this->idBusinessSector);

        $params = [
            'businessSector' => $businessSector,
            'platforms' => $platforms,
        ];
        $this->items = $this->loadItems();
        return view('livewire.business-sector-show', $params)->extends('layouts.master')->section('content');
    }
}
