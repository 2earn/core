<?php

namespace App\Http\Livewire;

use App\Models\Survey;
use App\Models\Target;
use Core\Enum\TargetType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Livewire\Component;

class SurveyCreateUpdate extends Component
{
    const DATE_FORMAT = "Y-m-d";

    public
        $idSurvey,
        $name,
        $status;

    public $enabled = false;
    public $published = false;
    public $updatable = false;

    public $commentable = TargetType::TARGET;
    public $likable = TargetType::TARGET;
    public $showResult = TargetType::TARGET;
    public $showAttchivementChrono = TargetType::TARGET;
    public $showAfterArchiving = TargetType::TARGET;
    public $showAttchivementPourcentage = TargetType::TARGET;

    public
        $startDate,
        $endDate,
        $enableDate,
        $disableDate,
        $openDate,
        $closeDate,
        $archivedDate,
        $goals,
        $description,
        $disabledBtnDescription;

    public $idTarget = null;
    public $target = null;
    public $targets = null;
    public $update = false;

    protected $listeners = [
        'deleteSurvey' => 'destroy'
    ];

    protected $rules = ['name' => 'required', 'description' => 'required'];

    public $targetTypes;

    public function mount(Request $request)
    {
        $idSurvey = $request->input('idSurvey');
        $this->idTarget = $request->input('idTarget');
        if (!is_null($idSurvey)) {
            $this->edit($idSurvey);
        }
    }

    public function resetFields()
    {
        $this->name = '';
        $this->description = '';
    }

    public function store()
    {
        $this->validate();
        try {
            $survey = Survey::create([
                'name' => $this->name,
                'description' => $this->description,
                'enabled' => $this->enabled,
                'published' => $this->published,
                'updatable' => $this->updatable,
                'showResult' => $this->showResult,
                'commentable' => $this->commentable,
                'likable' => $this->likable,
                'showAttchivementChrono' => $this->showAttchivementChrono,
                'showAfterArchiving' => $this->showAfterArchiving,
                'showAttchivementPourcentage' => $this->showAttchivementPourcentage,
                'startDate' => $this->startDate,
                'endDate' => $this->endDate,
                'goals' => $this->goals,
            ]);
            if (!is_null($this->idTarget)) {
                $survey->targets()->attach([$this->idTarget]);
            }
            return redirect()->route('survey_show', ['locale' => app()->getLocale(), 'idSurvey' => $survey->id])->with('success', Lang::get('Survey Created Successfully!!'));
        } catch (\Exception $exception) {
            return redirect()->route('surveys_index', app()->getLocale())->with('danger', Lang::get('Something goes wrong while creating Survey!!') . ' : ' . $exception->getMessage());
            $this->resetFields();
        }
    }

    public function edit($id)
    {
        $survey = Survey::findOrFail($id);
        $this->name = $survey->name;
        $this->description = $survey->description;
        $this->idSurvey = $survey->id;
        $this->enabled = $survey->enabled;
        $this->updatable = $survey->updatable;
        $this->commentable = $survey->commentable;
        $this->likable = $survey->likable;
        $this->showResult = $survey->showResult;
        $this->showAttchivementChrono = $survey->showAttchivementChrono;
        $this->showAfterArchiving = $survey->showAfterArchiving;
        $this->showAttchivementPourcentage = $survey->showAttchivementPourcentage;
        $this->startDate = date_format(new \DateTime($survey->startDate), self::DATE_FORMAT);
        $this->endDate = date_format(new \DateTime($survey->endDate), self::DATE_FORMAT);
        $this->goals = $survey->goals;
        if (!empty($survey->target)) {
            $this->target = $survey->target->first();
        }
        $this->update = true;
    }

    public function cancel()
    {
        return redirect()->route('surveys_index', app()->getLocale())->with('warning', Lang::get('Survey Operation cancelled!!'));
    }

    public function update()
    {
        $this->validate();
        try {
            Survey::where('id', $this->idSurvey)
                ->update([
                    'name' => $this->name,
                    'description' => $this->description,
                    'enabled' => $this->enabled,
                    'published' => $this->published,
                    'updatable' => $this->updatable,
                    'showResult' => $this->showResult,
                    'commentable' => $this->commentable,
                    'likable' => $this->likable,
                    'showAttchivementChrono' => $this->showAttchivementChrono,
                    'showAfterArchiving' => $this->showAfterArchiving,
                    'showAttchivementPourcentage' => $this->showAttchivementPourcentage,
                    'startDate' => $this->startDate,
                    'endDate' => $this->endDate,
                    'goals' => $this->goals,
                ]);
            if (!is_null($this->target)) {
                $survey = Survey::find($this->idSurvey);
                $survey->targets()->detach();
                $survey->targets()->attach([$this->target]);
            }
            return redirect()->route('survey_show', ['locale' => app()->getLocale(), 'idSurvey' => $this->idSurvey])->with('success', Lang::get('Survey Updated Successfully!!'));
        } catch (\Exception $exception) {
            $this->cancel();
            return redirect()->route('survey_show', ['locale' => app()->getLocale(), 'idSurvey' => $this->idSurvey])->with('danger', Lang::get('Something goes wrong while updating Survey!!') . ' : ' . $exception->getMessage());
        }
    }

    public function render()
    {
        $this->targets = Target::all();
        $this->targetTypes = TargetType::cases();
        return view('livewire.survey-create-update')->extends('layouts.master')->section('content');
    }
}
