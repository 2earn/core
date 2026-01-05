<?php

namespace App\Livewire;

use App\Enums\StatusSurvey;
use App\Services\CommentService;
use App\Services\Communication\Communication;
use App\Services\SurveyQuestionChoiceService;
use App\Services\SurveyQuestionService;
use App\Services\SurveyService;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Livewire\Component;

class SurveyShow extends Component
{
    public $idSurvey;
    public $routeRedirectionParams;
    public $currentRouteName;
    public $like;
    public $comment;
    public $disableNote;

    protected SurveyService $surveyService;
    protected SurveyQuestionService $questionService;
    protected SurveyQuestionChoiceService $choiceService;
    protected CommentService $commentService;

    public function boot(
        SurveyService $surveyService,
        SurveyQuestionService $questionService,
        SurveyQuestionChoiceService $choiceService,
        CommentService $commentService
    ) {
        $this->surveyService = $surveyService;
        $this->questionService = $questionService;
        $this->choiceService = $choiceService;
        $this->commentService = $commentService;
    }

    public function mount($idSurvey)
    {
        $this->idSurvey = $idSurvey;
        $this->routeRedirectionParams = ['locale' => app()->getLocale(), 'idSurvey' => $this->idSurvey];
        $this->currentRouteName = Route::currentRouteName();
        $this->like = $this->surveyService->hasUserLiked($this->idSurvey, auth()->user()->id);
    }


    public function removeQuestion($idQuestion)
    {
        $this->questionService->delete($idQuestion);
        return redirect()->route('surveys_show', $this->routeRedirectionParams)->with('success', Lang::get('Question Deleted Successfully'));
    }

    public function removeChoice($idChoice)
    {
        $this->choiceService->delete($idChoice);
        return redirect()->route('surveys_show', $this->routeRedirectionParams)->with('success', Lang::get('Choice Deleted Successfully'));
    }

    public function enable($id)
    {
        try {
            $this->surveyService->enable($id);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->route('surveys_show', $this->routeRedirectionParams)->with('danger', Lang::get('Something goes wrong while Enabling Survey'));
        }
        return redirect()->route('surveys_show', $this->routeRedirectionParams)->with('success', Lang::get('Survey Enabled Successfully'));
    }

    public function disable($id)
    {
        try {
            if (empty($this->disableNote)) {
                return redirect()->route('surveys_index', app()->getLocale())->with('danger', Lang::get('Something goes wrong while Disabling Survey'));
            }
            $this->surveyService->disable($id, $this->disableNote);

        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->route('surveys_index', app()->getLocale())->with('danger', Lang::get('Something goes wrong while Disabling Survey'));
        }

        return redirect()->route('surveys_index', app()->getLocale())->with('success', Lang::get('Survey Disabled Successfully'));
    }

    public function publish($id)
    {
        try {
            $this->surveyService->publish($id);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->route('surveys_show', $this->routeRedirectionParams)->with('danger', Lang::get('Something goes wrong while publishing Survey'));
        }
        return redirect()->route('surveys_show', $this->routeRedirectionParams)->with('success', Lang::get('Survey published Successfully'));
    }

    public function unpublish($id)
    {
        try {
            $this->surveyService->unpublish($id);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->route('surveys_show', $this->routeRedirectionParams)->with('danger', Lang::get('Something goes wrong while un publishing Survey'));
        }
        return redirect()->route('surveys_show', $this->routeRedirectionParams)->with('success', Lang::get('Survey un published Successfully'));
    }

    public function open($id)
    {
        try {
            $this->surveyService->canBeOpened($id);
            $this->surveyService->open($id);

        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->route('surveys_show', $this->routeRedirectionParams)->with('danger', Lang::get('Something goes wrong while opening SurveySurvey: ') . Lang::get($exception->getMessage()));
        }
        return redirect()->route('surveys_show', $this->routeRedirectionParams)->with('success', Lang::get('Survey Opened Successfully'));
    }

    public function close($id)
    {
        try {
            $this->surveyService->close($id);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->route('surveys_show', $this->routeRedirectionParams)->with('danger', Lang::get('Something goes wrong while closing Survey'));
        }
        return redirect()->route('surveys_show', $this->routeRedirectionParams)->with('success', Lang::get('Survey closed Successfully'));
    }

    public function archive($id)
    {
        try {
            $this->surveyService->archive($id);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->route('surveys_show', $this->routeRedirectionParams)->with('danger', Lang::get('Something goes wrong while arciving Survey'));
        }
        return redirect()->route('surveys_show', $this->routeRedirectionParams)->with('success', Lang::get('Survey arcived Successfully'));
    }

    public function changeUpdatable($id)
    {
        try {
            $survey = $this->surveyService->getById($id);
            $this->surveyService->changeUpdatable($id, !$survey->updatable);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->route('surveys_show', $this->routeRedirectionParams)->with('danger', Lang::get('Something goes wrong while updating Survey updatable property'));
        }
        return redirect()->route('surveys_show', $this->routeRedirectionParams)->with('success', Lang::get('Survey updatedd Successfully'));
    }


    public function addLike()
    {
        $this->surveyService->addLike($this->idSurvey, auth()->user()->id);
        $this->like = true;
    }

    public function addComment()
    {
        if (empty($this->comment)) {
            return redirect()->route('surveys_show', $this->routeRedirectionParams)->with('danger', Lang::get('Empty comment'));
        }
        $this->surveyService->addComment($this->idSurvey, auth()->user()->id, $this->comment);
        $this->comment = "";
    }

    public function validateComment($idComment)
    {
        $this->commentService->validateComment($idComment, auth()->user()->id);
    }

    public function deleteComment($idComment)
    {
        $this->commentService->deleteComment($idComment);
    }

    public function dislike()
    {
        $this->surveyService->removeLike($this->idSurvey, auth()->user()->id);
        $this->like = false;
    }

    public function duplicateSurvey($id)
    {
        try {
            $survey = $this->surveyService->findOrFail($id);
            if (intval($survey->status) <= StatusSurvey::OPEN->value) {
                return redirect()->route('surveys_show', $this->routeRedirectionParams)->with('danger', Lang::get('Only Opened surveys can be duplicated'));
            }
            $duplicate = Communication::duplicateSurvey($id);
            $this->routeRedirectionParams = ['locale' => app()->getLocale(), 'idSurvey' => $duplicate->id];
            return redirect()->route('surveys_show', $this->routeRedirectionParams)->with('success', Lang::get('Survey duplicated Successfully'));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            $this->routeRedirectionParams = ['locale' => app()->getLocale(), 'idSurvey' => $id];
            return redirect()->route('surveys_show', $this->routeRedirectionParams)->with('success', Lang::get('Survey duplication failed'));
        }
    }

    public function render()
    {
        $params = ['survey' => $this->surveyService->findOrFail($this->idSurvey)];
        return view('livewire.survey-show', $params)->extends('layouts.master')->section('content');
    }
}
