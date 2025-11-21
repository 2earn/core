<?php

namespace App\Livewire;

use Livewire\Component;

class PendingDealsRequests extends Component
{
    public function render()
    {
        return view('livewire.pending-deals-requests')->extends('layouts.master')->section('content');
    }
}
