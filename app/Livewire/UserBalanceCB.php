<?php

namespace App\Livewire;


use Livewire\Component;

class UserBalanceCB extends Component
{
    public function render()
    {
        return view('livewire.user-balance-c-b')->extends('layouts.master')->section('content');
    }

}
