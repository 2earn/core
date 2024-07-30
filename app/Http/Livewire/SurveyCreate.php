<?php

namespace App\Http\Livewire;

use Livewire\Component;

class SurveyCreate extends Component
{
    public function render()
    {
        return view('livewire.survey-create')->extends('layouts.master')->section('content');
    }
}
