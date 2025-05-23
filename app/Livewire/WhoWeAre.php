<?php

namespace App\Livewire;

use Livewire\Component;

class WhoWeAre extends Component
{
    public function render()
    {
        return view('livewire.who-we-are')->extends('layouts.master-without-nav-not-fluid')->section('content');
    }
}
