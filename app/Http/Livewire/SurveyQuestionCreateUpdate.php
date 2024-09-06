<?php

namespace App\Http\Livewire;

use App\Models\Survey;
use App\Models\SurveyQuestion;
use App\Models\TranslaleModel;
use Core\Enum\Selection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Livewire\Component;

class SurveyQuestionCreateUpdate extends Component
{
    public
        $idQuestion,
        $content,
        $selection = 2,
        $maxResponse = 1;

    public $idSurvey;

    public $update = false;

    public $selections = [
        ['name' => "Multiple", 'value' => 2],
        ['name' => "Unique", 'value' => 1]
    ];
    protected $rules = [
        'content' => 'required',
        'selection' => 'required'
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
    }


    public function cancel()
    {
        return redirect()->route('survey_show', ['locale' => app()->getLocale(), 'idSurvey' => $this->idSurvey])->with('warning', Lang::get('Question operation cancelled!!'));
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
            $this->validateMultiselection();
            SurveyQuestion::where('id', $this->idQuestion)
                ->update([
                    'content' => $this->content,
                    'selection' => $this->selection,
                    'maxResponse' => $this->maxResponse != "" ? $this->maxResponse : 0,
                ]);
            $translationModel = TranslaleModel::where('name', TranslaleModel::getTranslateName(SurveyQuestion::find($this->idQuestion), 'content'))->first();
            if (!is_null($translationModel)) {
                $translationModel->update(
                    [
                        'value' => $this->content . ' AR',
                        'valueFr' => $this->content . ' FR',
                        'valueEn' => $this->content . ' EN'
                    ]);
            }

        } catch (\Exception $exception) {
            return redirect()->route('survey_show', ['locale' => app()->getLocale(), 'idSurvey' => $this->idSurvey])->with('danger', Lang::get('Something goes wrong while updating Question!!') );
        }
        return redirect()->route('survey_show', ['locale' => app()->getLocale(), 'idSurvey' => $this->idSurvey])->with('success', Lang::get('Question Updated Successfully!!'));

    }

    public function validateMultiselection()
    {
        if ($this->selection == Selection::MULTIPLE->value && ($this->maxResponse == "" or $this->maxResponse == 0)) {
            throw new \Exception(Lang::get('Max response cannot be null when using multiple selections'));
        }
    }

    public function store()
    {
        $this->validate();
        try {
            $this->validateMultiselection();
            $surveyQuestion = SurveyQuestion::create([
                'content' => $this->content,
                'selection' => $this->selection,
                'maxResponse' => $this->maxResponse != "" ? $this->maxResponse : 0,
                'survey_id' => $this->idSurvey,
            ]);

            TranslaleModel::create(
                [
                    'name' => TranslaleModel::getTranslateName($surveyQuestion, 'content'),
                    'value' => $this->content . ' AR',
                    'valueFr' => $this->content . ' FR',
                    'valueEn' => $this->content . ' EN'
                ]);

        } catch (\Exception $exception) {
            return redirect()->route('survey_show', ['locale' => app()->getLocale(), 'idSurvey' => $this->idSurvey])->with('danger', Lang::get('Something goes wrong while creating Survey!!') );
        }
        return redirect()->route('survey_show', ['locale' => app()->getLocale(), 'idSurvey' => $this->idSurvey])->with('success', Lang::get('Survey Created Successfully!!'));
    }

    public function render()
    {
        $params ['survey'] = Survey::findOrFail($this->idSurvey);
        return view('livewire.survey-question-create-update', $params)->extends('layouts.master')->section('content');
    }
}
