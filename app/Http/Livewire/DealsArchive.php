<?php

namespace App\Http\Livewire;

use App\Models\Deal;
use App\Models\Survey;
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
        $DealsQuery = Deal::where('status', '=', DealStatus::Archived->value);

        if (strtoupper(auth()?->user()?->getRoleNames()->first()) == Survey::SUPER_ADMIN_ROLE_NAME) {

            if (!is_null($this->search) && !empty($this->search)) {
                $DealsQuery = $DealsQuery->where('name', 'like', '%' . $this->search . '%');
            }

        } else {
            if (!is_null($this->search) && !empty($this->search)) {
                $DealsQuery = $DealsQuery
                    ->where('name', 'like', '%' . $this->search . '%');
            }
        }
        return $DealsQuery->get();
    }

    public function render()
    {
        $params['deals'] = $this->getArchivedDeals();
        return view('livewire.deals-archive', $params)->extends('layouts.master')->section('content');
    }
}
