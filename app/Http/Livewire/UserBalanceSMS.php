<?php

namespace App\Http\Livewire;

use Core\Services\settingsManager;

use Livewire\Component;


class UserBalanceSMS extends Component
{


    public function render(settingsManager $settingsManager)
    {

        return view('livewire.user-balance-s-m-s')->extends('layouts.master')->section('content');;
    }

}