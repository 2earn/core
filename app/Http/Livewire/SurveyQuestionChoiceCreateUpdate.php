<?php

namespace App\Http\Livewire;

use App\Models\Survey;
use App\Models\SurveyQuestion;
use App\Models\SurveyQuestionChoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Route;
use Livewire\Component;

class SurveyQuestionChoiceCreateUpdate extends Component
{
    public $idQuestion;
    public $title;
    public $idSurvey;
    public $idChoice;
    public $update = false;
    protected $rules = [
        'title' => 'required'
    ];

    public function resetFields()
    {
        $this->title = '';
    }

    public function mount(Request $request)
    {
        $this->idSurvey = Route::current()->parameter('idSurvey');
        $this->idQuestion = Route::current()->parameter('idQuestion');
        $idChoice = $request->input('idChoice');
        if (!is_null($idChoice)) {
            $this->edit($idChoice);
        }
    }

    public function cancel()
    {
        return redirect()->route('survey_show', ['locale' => app()->getLocale(), 'idSurvey' => $this->idSurvey])->with('warning', Lang::get('Choice Operation cancelled!!'));
    }

    public function edit($idChoice)
    {
        $choice = SurveyQuestionChoice::findOrFail($idChoice);
        $this->idQuestion = $choice->question_id;
        $this->title = $choice->title;
        $this->idChoice = $idChoice;
        $this->update = true;
    }

    public function update()
    {
        $this->validate();
        try {
            $surveyQuestionChoice=    SurveyQuestionChoice::where('id', $this->idChoice)
                ->update(['title' => $this->title]);
            return redirect()->route('survey_show', ['locale' => app()->getLocale(), 'idSurvey' => $this->idSurvey])->with('success', Lang::get('Choice Updated Successfully!!'));
        } catch (\Exception $exception) {
            return redirect()->route('survey_show', ['locale' => app()->getLocale(), 'idSurvey' => $this->idSurvey])->with('danger', Lang::get('Something goes wrong while updating Choice!!') . ' : ' . $exception->getMessage());
        }
    }

    public function store()
    {
        $this->validate();
        try {
            $surveyQuestionChoice = SurveyQuestionChoice::create([
                'title' => $this->title,
                'question_id' => $this->idQuestion,
            ]);
            return redirect()->route('survey_show', ['locale' => app()->getLocale(), 'idSurvey' => $this->idSurvey])->with('success', Lang::get('Choice Created Successfully!!') . ' ' . $surveyQuestionChoice->content);
        } catch (\Exception $exception) {
            return redirect()->route('survey_show', ['locale' => app()->getLocale(), 'idSurvey' => $this->idSurvey])->with('danger', Lang::get('Something goes wrong while creating Choice!!') . ' : ' . $exception->getMessage());
            $this->resetFields();
        }
    }


    public function render()
    {
        $params = [
            'idSurvey' => $this->idSurvey,
            'idQuestion' => $this->idQuestion,
            'question' => SurveyQuestion::find($this->idQuestion),
            'survey' => Survey::find($this->idSurvey)
        ];
        return view('livewire.survey-question-choice-create-update', $params)->extends('layouts.master')->section('content');
    }
}
