<?php

namespace App\Http\Livewire;

use Livewire\Attributes\Url;
use Livewire\Component;

class SurveyShow extends Component
{
    public $idServey;

    public function mount($idServey)
    {
        $this->idServey = $idServey;
    }

    public function render()
    {
        $params = [];
        return view('livewire.survey-show', ["params" => $params])->extends('layouts.master')->section('content');
    }
}
