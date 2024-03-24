<?php

namespace App\Http\Livewire;

use Livewire\Component;

class UsersList extends Component
{
    public function render()
    {
        return view('livewire.user-list')->extends('layouts.master')->section('content');
    }
}






