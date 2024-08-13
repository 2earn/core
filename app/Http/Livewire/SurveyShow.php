<?php

namespace App\Http\Livewire;

use App\Models\Survey;
use App\Models\SurveyQuestion;
use App\Models\SurveyQuestionChoice;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Route;
use Livewire\Attributes\Url;
use Livewire\Component;

class SurveyShow extends Component
{
    public $idSurvey;
    public $routeRedirectionParams;
    public $currentRouteName;
    public $like;
    public $comment;
    public $disableNote;

    public function mount($idSurvey)
    {
        $this->idSurvey = $idSurvey;
        $this->routeRedirectionParams = ['locale' => app()->getLocale(), 'idSurvey' => $this->idSurvey];
        $this->currentRouteName = Route::currentRouteName();
        $this->like = Survey::find($this->idSurvey)->likes()?->count() ? true : false;
    }

    public function removeQuestion($idQuestion)
    {
        SurveyQuestion::findOrFail($idQuestion)->delete();
        return redirect()->route('survey_show', $this->routeRedirectionParams)->with('success', Lang::get('Question Deleted Successfully!!'));
    }

    public function removeChoice($idChoice)
    {
        SurveyQuestionChoice::findOrFail($idChoice)->delete();
        return redirect()->route('survey_show', $this->routeRedirectionParams)->with('success', Lang::get('Choice Deleted Successfully!!'));
    }

    public function enable($id)
    {
        try {
            Survey::enable($id);
        } catch (\Exception $exception) {
            return redirect()->route('survey_show', $this->routeRedirectionParams)->with('danger', Lang::get('Something goes wrong while Enabling Survey!!') . ' : ' . $exception->getMessage());
        }
        return redirect()->route('survey_show', $this->routeRedirectionParams)->with('success', Lang::get('Survey Enabled Successfully!!'));
    }

    public function disable($id)
    {
        try {
            if (!empty($this->disableNote)) {
                Survey::disable($id, $this->disableNote);
            } else {
                return redirect()->route('surveys_index', app()->getLocale())->with('danger', Lang::get('Something goes wrong while Disabling Survey!!') . ' : ');
            }
        } catch (\Exception $exception) {
            return redirect()->route('surveys_index', app()->getLocale())->with('danger', Lang::get('Something goes wrong while Disabling Survey!!') . ' : ' . $exception->getMessage());
        }
        return redirect()->route('surveys_index', app()->getLocale())->with('success', Lang::get('Survey Disabled Successfully!!'));
    }

    public function publish($id)
    {
        try {
            Survey::publish($id);
        } catch (\Exception $exception) {
            return redirect()->route('survey_show', $this->routeRedirectionParams)->with('danger', Lang::get('Something goes wrong while publishing Survey!!') . ' : ' . $exception->getMessage());
        }
        return redirect()->route('survey_show', $this->routeRedirectionParams)->with('success', Lang::get('Survey published Successfully!!'));
    }

    public function unpublish($id)
    {
        try {
            Survey::unpublish($id);
        } catch (\Exception $exception) {
            return redirect()->route('survey_show', $this->routeRedirectionParams)->with('danger', Lang::get('Something goes wrong while un publishing Survey!!') . ' : ' . $exception->getMessage());
        }
        return redirect()->route('survey_show', $this->routeRedirectionParams)->with('success', Lang::get('Survey un published Successfully!!'));
    }

    public function open($id)
    {
        try {
            Survey::open($id);
        } catch (\Exception $exception) {
            return redirect()->route('survey_show', $this->routeRedirectionParams)->with('danger', Lang::get('Something goes wrong while opening Survey!!') . ' : ' . $exception->getMessage());
        }
        return redirect()->route('survey_show', $this->routeRedirectionParams)->with('success', Lang::get('Survey Opened Successfully!!'));
    }

    public function close($id)
    {
        try {
            Survey::close($id);
        } catch (\Exception $exception) {
            return redirect()->route('survey_show', $this->routeRedirectionParams)->with('danger', Lang::get('Something goes wrong while closing Survey!!') . ' : ' . $exception->getMessage());
        }            return redirect()->route('survey_show', $this->routeRedirectionParams)->with('success', Lang::get('Survey closed Successfully!!'));

    }

    public function archive($id)
    {
        try {
            Survey::archive($id);
        } catch (\Exception $exception) {
            return redirect()->route('survey_show', $this->routeRedirectionParams)->with('danger', Lang::get('Something goes wrong while arciving Survey!!') . ' : ' . $exception->getMessage());
        }
        return redirect()->route('survey_show', $this->routeRedirectionParams)->with('success', Lang::get('Survey arcived Successfully!!'));
    }

    public function like()
    {
        $survey = Survey::find($this->idSurvey);
        $survey->likes()->create(['user_id' => auth()->user()->id]);
        $this->like = true;
    }

    public function addComment()
    {
        $survey = Survey::find($this->idSurvey);
        $survey->comments()->create(['user_id' => auth()->user()->id, 'content' => $this->comment]);
    }

    public function dislike()
    {
        Survey::find($this->idSurvey)->likes()->delete();
        $this->like = false;
    }

    public function render()
    {
        $params = ['survey' => Survey::findOrFail($this->idSurvey)];
        return view('livewire.survey-show', $params)->extends('layouts.master')->section('content');
    }
}
