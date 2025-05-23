<?php

namespace App\Livewire;

use Core\Services\settingsManager;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class UserBalanceBFS extends Component
{
    public function render()
    {
        return view('livewire.user-balance-b-f-s')->extends('layouts.master')->section('content');
    }
}
