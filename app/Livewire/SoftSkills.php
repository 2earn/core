<?php

namespace App\Livewire;

use Livewire\Component;

class SoftSkills extends Component
{
    public function render()
    {
        return view('livewire.soft-skills')->extends('layouts.master')->section('content');
    }
}
