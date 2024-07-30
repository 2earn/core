<?php

namespace App\Http\Livewire;

use Livewire\Component;

class SurveyParicipate extends Component
{

    public $idServey;

    public function mount($idServey)
    {
        $this->idServey = $idServey;
    }

    public function render()
    {
        $params = [];
        return view('livewire.survey-paricipate', ["params" => $params])->extends('layouts.master')->section('content');
    }
}
