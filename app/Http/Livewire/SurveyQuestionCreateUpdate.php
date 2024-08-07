<?php

namespace App\Http\Livewire;

use App\Models\Survey;
use App\Models\SurveyQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Livewire\Component;

class SurveyQuestionCreateUpdate extends Component
{
    public
        $idQuestion,
        $nameServey,
        $content,
        $selection,
        $maxResponse;

    public $idSurvey;

    public $update = false;

    protected $rules = [
        'content' => 'required',
        'selection' => 'required',
        'maxResponse' => 'required'
    ];

    public function mount(Request $request)
    {
        $idQuestion = $request->input('IdQuestion');
        if (!is_null($idQuestion)) {
            $this->edit($idQuestion);
        }
    }


    public function resetFields()
    {
        $this->content = '';
        $this->selection = '';
        $this->maxResponse = '';
    }


    public function cancel()
    {
        return redirect()->route('survey_show', ['locale' => app()->getLocale(), 'idSurvey' => $this->idSurvey])->with('warning', Lang::get('Choice Operation cancelled!!'));
    }

    public function edit($idQuestion)
    {
        $question = SurveyQuestion::findOrFail($idQuestion);
        $this->idQuestion = $idQuestion;
        $this->content = $question->content;
        $this->selection = $question->selection;
        $this->maxResponse = $question->maxResponse;
        $this->idSurvey = $question->survey_id;
        $this->update = true;
    }

    public function update()
    {
        $this->validate();
        try {
            SurveyQuestion::where('id', $this->idQuestion)
                ->update([
                    'content' => $this->content,
                    'selection' => $this->selection,
                    'maxResponse' => $this->maxResponse,
                ]);
            return redirect()->route('survey_show', ['locale' => app()->getLocale(), 'idSurvey' => $this->idSurvey])->with('success', Lang::get('Question Updated Successfully!!'));
        } catch (\Exception $exception) {
            return redirect()->route('survey_show', ['locale' => app()->getLocale(), 'idSurvey' => $this->idSurvey])->with('danger', Lang::get('Something goes wrong while updating Question!!') . ' : ' . $exception->getMessage());
        }
    }

    public function store()
    {
        $this->validate();
        try {
            $surveyQuestion = SurveyQuestion::create([
                'content' => $this->content,
                'selection' => $this->selection,
                'maxResponse' => $this->maxResponse,
                'survey_id' => $this->idSurvey,
            ]);
            return redirect()->route('survey_show', ['locale' => app()->getLocale(), 'idSurvey' => $this->idSurvey])->with('success', Lang::get('Survey Created Successfully!!') . ' ' . $surveyQuestion->content);
        } catch (\Exception $exception) {
            return redirect()->route('survey_show', ['locale' => app()->getLocale(), 'idSurvey' => $this->idSurvey])->with('danger', Lang::get('Something goes wrong while creating Survey!!') . ' : ' . $exception->getMessage());
            $this->resetFields();
        }
    }

    public function render()
    {
        $params ['survey'] = Survey::findOrFail($this->idSurvey);
        return view('livewire.survey-question-create-update', $params)->extends('layouts.master')->section('content');
    }
}
