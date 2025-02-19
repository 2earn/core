<?php

namespace App\Http\Livewire;

use App\Models\BusinessSector;
use Core\Models\Platform;
use Illuminate\Support\Facades\Lang;
use Livewire\Component;

class BusinessSectorShow extends Component
{
    public $items = [];
    protected $listeners = [
        'deletebusinessSector' => 'deletebusinessSector'
    ];

    public function mount($id)
    {
        $this->idBusinessSector = $id;
    }

    public function deletebusinessSector($idBusinessSector)
    {
        BusinessSector::findOrFail($idBusinessSector)->delete();
        return redirect()->route('business_sector_index', ['locale' => app()->getLocale()])->with('success', Lang::get('Business sector Deleted Successfully'));
    }

    public function loadItems()
    {
        return BusinessSector::find($this->idBusinessSector)
            ->platforms()
            ->with('deals.items')
            ->get()
            ->pluck('deals')
            ->flatten()
            ->pluck('items')
            ->flatten();
    }

    public function render()
    {
        $businessSector = BusinessSector::find($this->idBusinessSector);
        if (is_null($businessSector)) {
            $this->redirect()->route('business_sector_index', ['locale' => app()->getLocale()]);
        }
        $params = [
            'businessSector' => $businessSector,
            'platforms' => Platform::where('business_sector_id', $this->idBusinessSector),
        ];
        $this->items = $this->loadItems();
        return view('livewire.business-sector-show', $params)->extends('layouts.master')->section('content');
    }
}
