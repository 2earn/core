<?php

namespace App\Http\Livewire;

use App\Models\Deal;
use Livewire\Component;

class DealsShow extends Component
{
    public $idDeal;

    public function mount($id)
    {
        $this->idDeal = $id;
    }

    public function render()
    {
        $params = ['deal' => Deal::find($this->idDeal)];
        return view('livewire.deals-show',$params)->extends('layouts.master')->section('content');
    }
}
