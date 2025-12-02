<?php

namespace App\Livewire;

use App\Models\Survey;
use App\Models\User;
use App\Services\Deals\DealService;
use Core\Enum\DealStatus;
use Illuminate\Support\Facades\Route;
use Livewire\Component;
use Livewire\WithPagination;

class DealsArchive extends Component
{
    use WithPagination;

    public $search = '';
    public $currentRouteName;

    public function mount()
    {
        $this->currentRouteName = Route::currentRouteName();
    }

    public function getArchivedDeals()
    {
        return app(DealService::class)->getArchivedDeals($this->search, User::isSuperAdmin());
    }

    public function render()
    {
        $params['deals'] = $this->getArchivedDeals();
        return view('livewire.deals-archive', $params)->extends('layouts.master')->section('content');
    }
}
