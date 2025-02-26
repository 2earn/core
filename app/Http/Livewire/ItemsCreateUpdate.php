<?php

namespace App\Http\Livewire;

use Livewire\Component;

class ItemsCreateUpdate extends Component
{
    public function render()
    {
        return view('livewire.items-create-update')->extends('layouts.master')->section('content');
    }
}
