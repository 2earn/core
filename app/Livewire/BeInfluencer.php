<?php

namespace App\Livewire;

use Livewire\Component;

class BeInfluencer extends Component
{
    public function render()
    {
        return view('livewire.be-influencer')->extends('layouts.master')->section('content');
    }
}
