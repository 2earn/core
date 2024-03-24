<?php

namespace App\Http\Livewire;

use Livewire\Component;

class UserPurchaseHistory extends Component
{
    public function render()
    {
        return view('livewire.user-purchase-history')->extends('layouts.master')->section('content');
    }
}






