<?php

namespace App\Livewire;

use App\Services\BusinessSector\BusinessSectorService;
use Livewire\Component;

class BussinessSectorsHome extends Component
{
    public $businessSectors = [];
    protected $businessSectorService;

    public function boot(BusinessSectorService $businessSectorService)
    {
        $this->businessSectorService = $businessSectorService;
    }

    public function mount()
    {
        $this->businessSectors = $this->businessSectorService->getBusinessSectors([
            'with' => ['logoImage', 'thumbnailsImage'],
            'order_by' => 'created_at',
            'order_direction' => 'desc'
        ])->take(4);
    }

    public function render()
    {
        $params = [];
        return view('livewire.bussiness-sectors-home', $params)->extends('layouts.master')->section('content');
    }
}
