<?php

namespace App\Livewire;

use Livewire\Component;

class PendingPlatformRequests extends Component
{
    public function render()
    {
        return view('livewire.pending-platform-requests')->extends('layouts.master')->section('content');
    }
}
