<?php

namespace App\Http\Livewire;

use Livewire\Component;

class GeneralTermsOfUse extends Component
{
    public function render()
    {
        return view('livewire.general-terms-of-use')->extends('layouts.master-without-nav-not-fluid')->section('content');
    }
}
