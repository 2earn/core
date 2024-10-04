<?php

namespace App\Http\Livewire;

use Livewire\Component;

class HardSkills extends Component
{
    public function render()
    {
        return view('livewire.hard-skills')->extends('layouts.master')->section('content');
    }
}
