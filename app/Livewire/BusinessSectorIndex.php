<?php

namespace App\Livewire;

use App\Services\BusinessSector\BusinessSectorService;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Route;
use Livewire\Component;
use Livewire\WithPagination;

class BusinessSectorIndex extends Component
{
    use WithPagination;

    const PAGE_SIZE = 4;
    public $search = '';
    public $page ;
    public $currentRouteName;
    protected $paginationTheme = 'bootstrap';
    protected $businessSectorService;

    public function boot(BusinessSectorService $businessSectorService)
    {
        $this->businessSectorService = $businessSectorService;
    }


    protected $listeners = [
        'deleteBusinessSector' => 'deleteBusinessSector'
    ];

    public function mount()
    {
        $this->currentRouteName = Route::currentRouteName();
    }

    public function resetPage($pageName = 'page')
    {
        $this->setPage(1, $pageName);
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function deleteBusinessSector($idBusinessSector)
    {
        $this->businessSectorService->deleteBusinessSector($idBusinessSector);
        return redirect()->route('business_sector_index', ['locale' => app()->getLocale()])->with('success', Lang::get('Business sector Deleted Successfully'));
    }

    public function render()
    {
        $params['business_sectors'] = $this->businessSectorService->getBusinessSectors(['search' => $this->search, 'PAGE_SIZE' => self::PAGE_SIZE, 'page' => $this->page]);
        return view('livewire.business-sector-index', $params)->extends('layouts.master')->section('content');
    }
}
