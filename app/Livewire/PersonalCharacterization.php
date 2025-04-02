<?php

namespace App\Livewire;

use Livewire\Component;

class PersonalCharacterization extends Component
{
    public function render()
    {
        return view('livewire.personal-characterization')->extends('layouts.master')->section('content');
    }
}
