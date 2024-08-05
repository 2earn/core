<?php

namespace App\Http\Livewire;

use App\Models\Survey;
use Illuminate\Support\Facades\Lang;
use Livewire\Attributes\Url;
use Livewire\Component;

class SurveyShow extends Component
{
    public $idSurvey;
    public $routeRedirectionParams;

    public function mount($idSurvey)
    {
        $this->idSurvey = $idSurvey;
        $this->routeRedirectionParams = ['locale' => app()->getLocale(), 'idSurvey' => $this->idSurvey];
    }

    public function enable($id)
    {
        try {
            Survey::enable($id);
            return redirect()->route('survey_show', $this->routeRedirectionParams)->with('success', Lang::get('Survey Enabled Successfully!!'));
        } catch (\Exception $exception) {
            return redirect()->route('survey_show', $this->routeRedirectionParams)->with('danger', Lang::get('Something goes wrong while Enabling Survey!!') . ' : ' . $exception->getMessage());
        }
    }

    public function disable($id)
    {
        try {
            Survey::disable($id);
            return redirect()->route('survey_show', $this->routeRedirectionParams)->with('success', Lang::get('Survey Disabled Successfully!!'));
        } catch (\Exception $exception) {
            return redirect()->route('survey_show', $this->routeRedirectionParams)->with('danger', Lang::get('Something goes wrong while Disabling Survey!!') . ' : ' . $exception->getMessage());
        }
    }

    public function open($id)
    {
        try {
            Survey::open($id);
            return redirect()->route('survey_show', $this->routeRedirectionParams)->with('success', Lang::get('Survey Opened Successfully!!'));
        } catch (\Exception $exception) {
            return redirect()->route('survey_show', $this->routeRedirectionParams)->with('danger', Lang::get('Something goes wrong while opening Survey!!') . ' : ' . $exception->getMessage());
        }
    }

    public function close($id)
    {
        try {
            Survey::close($id);
            return redirect()->route('survey_show', $this->routeRedirectionParams)->with('success', Lang::get('Survey closed Successfully!!'));
        } catch (\Exception $exception) {
            return redirect()->route('survey_show', $this->routeRedirectionParams)->with('danger', Lang::get('Something goes wrong while closing Survey!!') . ' : ' . $exception->getMessage());
        }
    }

    public function archive($id)
    {
        try {
            Survey::archive($id);
            return redirect()->route('survey_show', $this->routeRedirectionParams)->with('success', Lang::get('Survey arcived Successfully!!'));
        } catch (\Exception $exception) {
            return redirect()->route('survey_show', $this->routeRedirectionParams)->with('danger', Lang::get('Something goes wrong while arciving Survey!!') . ' : ' . $exception->getMessage());
        }
    }

    public function render()
    {
        $params ['survey'] = Survey::findOrFail($this->idSurvey);
        return view('livewire.survey-show', $params)->extends('layouts.master')->section('content');
    }
}
