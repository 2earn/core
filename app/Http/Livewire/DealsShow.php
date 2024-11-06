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
        $deal = Deal::find($this->idDeal);
        if (is_null($deal)) {
            $this->redirect()->route('deals_index', ['locale' => app()->getLocale()]);
        }
        $params = ['deal' => $deal];
        return view('livewire.deals-show', $params)->extends('layouts.master')->section('content');
    }
}
