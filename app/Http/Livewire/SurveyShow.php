<?php

namespace App\Http\Livewire;

use App\Models\Comment;
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
        $this->like = Survey::whereHas('likes', function ($q) {
            $q->where('user_id', auth()->user()->id)->where('likable_id', $this->idSurvey);
        })->exists();
    }

    public function removeQuestion($idQuestion)
    {
        SurveyQuestion::findOrFail($idQuestion)->delete();
        return redirect()->route('surveys_show', $this->routeRedirectionParams)->with('success', Lang::get('Question Deleted Successfully!!'));
    }

    public function removeChoice($idChoice)
    {
        SurveyQuestionChoice::findOrFail($idChoice)->delete();
        return redirect()->route('surveys_show', $this->routeRedirectionParams)->with('success', Lang::get('Choice Deleted Successfully!!'));
    }

    public function enable($id)
    {
        try {
            Survey::enable($id);
        } catch (\Exception $exception) {
            return redirect()->route('surveys_show', $this->routeRedirectionParams)->with('danger', Lang::get('Something goes wrong while Enabling Survey!!'));
        }
        return redirect()->route('surveys_show', $this->routeRedirectionParams)->with('success', Lang::get('Survey Enabled Successfully!!'));
    }

    public function disable($id)
    {
        try {
            if (empty($this->disableNote)) {
                return redirect()->route('surveys_index', app()->getLocale())->with('danger', Lang::get('Something goes wrong while Disabling Survey!!'));
            }
            Survey::disable($id, $this->disableNote);

        } catch (\Exception $exception) {
            return redirect()->route('surveys_index', app()->getLocale())->with('danger', Lang::get('Something goes wrong while Disabling Survey!!'));
        }

        return redirect()->route('surveys_index', app()->getLocale())->with('success', Lang::get('Survey Disabled Successfully!!'));
    }

    public function publish($id)
    {
        try {
            Survey::publish($id);
        } catch (\Exception $exception) {
            return redirect()->route('surveys_show', $this->routeRedirectionParams)->with('danger', Lang::get('Something goes wrong while publishing Survey!!'));
        }
        return redirect()->route('surveys_show', $this->routeRedirectionParams)->with('success', Lang::get('Survey published Successfully!!'));
    }

    public function unpublish($id)
    {
        try {
            Survey::unpublish($id);
        } catch (\Exception $exception) {
            return redirect()->route('surveys_show', $this->routeRedirectionParams)->with('danger', Lang::get('Something goes wrong while un publishing Survey!!'));
        }
        return redirect()->route('surveys_show', $this->routeRedirectionParams)->with('success', Lang::get('Survey un published Successfully!!'));
    }

    public function open($id)
    {
        try {
            Survey::canBeOpened($id);
            Survey::open($id);

        } catch (\Exception $exception) {
            return redirect()->route('surveys_show', $this->routeRedirectionParams)->with('danger', Lang::get('Something goes wrong while opening SurveySurvey: ') . Lang::get($exception->getMessage()));
        }
        return redirect()->route('surveys_show', $this->routeRedirectionParams)->with('success', Lang::get('Survey Opened Successfully!!'));
    }

    public function close($id)
    {
        try {
            Survey::close($id);
        } catch (\Exception $exception) {
            return redirect()->route('surveys_show', $this->routeRedirectionParams)->with('danger', Lang::get('Something goes wrong while closing Survey!!'));
        }
        return redirect()->route('surveys_show', $this->routeRedirectionParams)->with('success', Lang::get('Survey closed Successfully!!'));

    }

    public function archive($id)
    {
        try {
            Survey::archive($id);
        } catch (\Exception $exception) {
            return redirect()->route('surveys_show', $this->routeRedirectionParams)->with('danger', Lang::get('Something goes wrong while arciving Survey!!'));
        }
        return redirect()->route('surveys_show', $this->routeRedirectionParams)->with('success', Lang::get('Survey arcived Successfully!!'));
    }

    public function changeUpdatable($id)
    {
        try {
            $survey = Survey::find($id);
            Survey::changeUpdatable($id, !$survey->updatable);
        } catch (\Exception $exception) {
            return redirect()->route('surveys_show', $this->routeRedirectionParams)->with('danger', Lang::get('Something goes wrong while updating Survey updatable property!!'));
        }
        return redirect()->route('surveys_show', $this->routeRedirectionParams)->with('success', Lang::get('Survey updatedd Successfully!!'));
    }


    public function like()
    {
        $survey = Survey::find($this->idSurvey);
        $survey->likes()->create(['user_id' => auth()->user()->id]);
        $this->like = true;
    }

    public function addComment()
    {
        if (empty($this->comment)) {
            return redirect()->route('surveys_show', $this->routeRedirectionParams)->with('danger', Lang::get('Empty comment !!'));
        }
        $survey = Survey::find($this->idSurvey);
        $survey->comments()->create(['user_id' => auth()->user()->id, 'content' => $this->comment]);
        $this->comment = "";

    }

    public function validateComment($idComment)
    {
        Comment::validate($idComment);
    }

    public function deleteComment($idComment)
    {
        Comment::deleteComment($idComment);
    }

    public function dislike()
    {
        $likes = Survey::find($this->idSurvey)->likes()->get();
        foreach ($likes as $like) {
            if ($like->user_id == auth()->user()->id) {
                $like->delete();
            }
        }
        $this->like = false;
    }

    public function render()
    {
        $params = ['survey' => Survey::findOrFail($this->idSurvey)];
        return view('livewire.survey-show', $params)->extends('layouts.master')->section('content');
    }
}
