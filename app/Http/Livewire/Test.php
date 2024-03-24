<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Test extends Component
{
    public $test = '121';

    public function render()
    {
        return view('livewire.test')->extends('layouts.master')->section('content');
    }
}
