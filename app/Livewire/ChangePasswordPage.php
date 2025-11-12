<?php

namespace App\Livewire;

use Livewire\Component;

class ChangePasswordPage extends Component
{
    public function render()
    {
        return view('livewire.change-password-page')->extends('layouts.master')->section('content');
    }
}

