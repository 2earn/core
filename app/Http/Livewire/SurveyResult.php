<?php

namespace App\Http\Livewire;

use Livewire\Component;

class SurveyResult extends Component
{
    public $idServey;

    public function mount($idServey)
    {
        $this->idServey = $idServey;
    }

    public function render()
    {
        $params = [];
        return view('livewire.survey-result', ["params" => $params])->extends('layouts.master')->section('content');
    }
}
