<?php

namespace App\Livewire;

use Core\Services\settingsManager;
use Livewire\Component;

class UserManager extends Component
{

    public function render()
    {
        return view('livewire.user-manager')->extends('layouts.master')->section('content');
    }



}
