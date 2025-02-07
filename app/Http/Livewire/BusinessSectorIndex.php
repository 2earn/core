<?php

namespace App\Http\Livewire;

use App\Models\BusinessSector;
use App\Models\Faq;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Route;
use Livewire\Component;
use Livewire\WithPagination;

class BusinessSectorIndex extends Component
{
    use WithPagination;

    const PAGE_SIZE = 5;
    public $search = '';
    public $currentRouteName;
    protected $paginationTheme = 'bootstrap';

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

    public function deletebusinessSector($idBusinessSector)
    {
        BusinessSector::findOrFail($idBusinessSector)->delete();
        return redirect()->route('business_sector_index', ['locale' => app()->getLocale()])->with('success', Lang::get('Business sector Deleted Successfully'));
    }
    public function render()
    {
        if (!is_null($this->search) && !empty($this->search)) {
            $params['business_sectors'] = BusinessSector::where('name', 'like', '%' . $this->search . '%')->orderBy('created_at', 'desc')->paginate(self::PAGE_SIZE);
        } else {
            $params['business_sectors'] = BusinessSector::orderBy('created_at', 'desc')->paginate(self::PAGE_SIZE);
        }
        return view('livewire.business-sector-index', $params)->extends('layouts.master')->section('content');
    }
}
