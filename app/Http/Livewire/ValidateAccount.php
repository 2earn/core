<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Crypt;
use Livewire\Component;

class ValidateAccount extends Component
{
    public $paramIdUser;
    public $idjo;

    public function mount()
    {
    }

    public function render()
    {
        return view('livewire.validate-account', ['paramIdUser' => $this->paramIdUser])->extends('layouts.master')->section('content');
    }
}
