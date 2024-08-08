<?php

namespace App\Http\Livewire;

use App\Models\Comment;
use App\Models\Like;
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

    public function mount($idSurvey)
    {
        $this->idSurvey = $idSurvey;
        $this->routeRedirectionParams = ['locale' => app()->getLocale(), 'idSurvey' => $this->idSurvey];
        $this->currentRouteName = Route::currentRouteName();
        $this->like = is_null(Like::where('user_id', '=', auth()->user()->id)->where('survey_id', '=', $this->idSurvey)) ? false : true;
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

    public function publish($id)
    {
        try {
            Survey::publish($id);
            return redirect()->route('survey_show', $this->routeRedirectionParams)->with('success', Lang::get('Survey published Successfully!!'));
        } catch (\Exception $exception) {
            return redirect()->route('survey_show', $this->routeRedirectionParams)->with('danger', Lang::get('Something goes wrong while publishing Survey!!') . ' : ' . $exception->getMessage());
        }
    }

    public function unpublish($id)
    {
        try {
            Survey::unpublish($id);
            return redirect()->route('survey_show', $this->routeRedirectionParams)->with('success', Lang::get('Survey un published Successfully!!'));
        } catch (\Exception $exception) {
            return redirect()->route('survey_show', $this->routeRedirectionParams)->with('danger', Lang::get('Something goes wrong while un publishing Survey!!') . ' : ' . $exception->getMessage());
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

    public function like()
    {
        Like::create(['user_id' => auth()->user()->id, 'survey_id' => $this->idSurvey]);

        $this->like = true;
    }

    public function addComment()
    {
        Comment::create([
            'user_id' => auth()->user()->id,
            'content' => $this->comment,
            'survey_id' => $this->idSurvey
        ]);
    }

    public function dislike()
    {
        Like::where('user_id', '=', auth()->user()->id)->where('survey_id', '=', $this->idSurvey)->delete();
        $this->like = false;
    }

    public function render()
    {
        $params = [
            'survey' => Survey::findOrFail($this->idSurvey),
        ];
        return view('livewire.survey-show', $params)->extends('layouts.master')->section('content');
    }
}
