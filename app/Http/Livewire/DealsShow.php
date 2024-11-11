<?php

namespace App\Http\Livewire;

use App\Models\Deal;
use Illuminate\Support\Facades\Route;
use Livewire\Component;

class DealsShow extends Component
{
    public $idDeal, $currentRouteName;

    public function mount($id)
    {
        $this->currentRouteName = Route::currentRouteName();
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
