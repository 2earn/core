<?php

namespace App\Livewire;

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
        $businessSector = BusinessSector::with(['logoImage', 'thumbnailsImage', 'thumbnailsHomeImage'])
            ->find($this->idBusinessSector);

        if (is_null($businessSector)) {
            redirect()->route('business_sector_index', ['locale' => app()->getLocale()]);
        }

        // Eager load all relationships to avoid N+1 queries
        $platforms = Platform::with([
            'logoImage',
            'deals' => function($query) {
                $query->where('start_date', '<=', now())
                      ->where('end_date', '>=', now());
            },
            'deals.items' => function($query) {
                $query->where('ref', '!=', '#0001');
            },
            'deals.items.thumbnailsImage'
        ])
        ->where('enabled', true)
        ->where('business_sector_id', $this->idBusinessSector)
        ->orderBy('created_at')
        ->get();

        $params = [
            'businessSector' => $businessSector,
            'platforms' => $platforms,
        ];
        $this->items = $this->loadItems();
        return view('livewire.business-sector-show', $params)->extends('layouts.master')->section('content');
    }
}
