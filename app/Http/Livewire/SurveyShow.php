<?php

namespace App\Http\Livewire;

use App\Models\Survey;
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
        $params ['survey'] = Survey::findOrFail($this->idServey);
        return view('livewire.survey-show', $params)->extends('layouts.master')->section('content');
    }
}
