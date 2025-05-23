<?php

namespace App\Livewire;

use Illuminate\Http\Request;
use Livewire\Component;

class ValidateAccount extends Component
{
    public $paramIdUser;

    public function mount(Request $request)
    {
        $this->paramIdUser = $request->input('paramIdUser');;
    }

    public function render()
    {
        return view('livewire.validate-account', ['paramIdUser' => $this->paramIdUser])->extends('layouts.master')->section('content');
    }
}
