<?php

namespace App\Http\Livewire;

use Livewire\Component;

class SuccessfulSharingPool extends Component
{
    public function render()
    {
        return view('livewire.successful-sharing-pool')->extends('layouts.master')->section('content');
    }
}
