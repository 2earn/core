<?php

namespace App\Livewire;

use Livewire\Component;

class SmsIndex extends Component
{
    public function render()
    {
        return view('livewire.sms-index')->extends('layouts.master')->section('content');
    }
}

