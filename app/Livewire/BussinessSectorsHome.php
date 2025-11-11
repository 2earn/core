<?php

namespace App\Livewire;

use App\Models\BusinessSector;
use Livewire\Component;

class BussinessSectorsHome extends Component
{
    public $businessSectors = [];

    public function mount()
    {
        $this->businessSectors = BusinessSector::limit(4)->get();

    }

    public function render()
    {
        $params = [];
        return view('livewire.bussiness-sectors-home', $params)->extends('layouts.master')->section('content');
    }
}
