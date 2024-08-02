<?php

namespace App\Http\Livewire;

use App\Models\Survey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Livewire\Component;

class SurveyCreateUpdate extends Component
{

    public $idSurvey, $name, $enabled, $archived, $startDate, $endDate, $participationLimit, $updatable, $showResult, $achievement, $showAchievement, $description;
    public $update = false;
    protected $listeners = [
        'deleteSurvey' => 'destroy'
    ];
    // Validation Rules
    protected $rules = [
        'name' => 'required',
        'description' => 'required'
    ];

    public function resetFields()
    {
        $this->name = '';
        $this->description = '';
    }

    public function store()
    {
        $this->validate();
        try {
            Survey::create([
                'name' => $this->name,
                'description' => $this->description,
                'enabled' => $this->enabled,
                'archived' => $this->archived,
                'updatable' => $this->updatable,
                'showResult' => $this->showResult,
                'showAchievement' => $this->showAchievement,
            ]);
            return redirect()->route('surveys_index', app()->getLocale())->with('success', Lang::get('Survey Created Successfully!!'));
        } catch (\Exception $exception) {
            return redirect()->route('surveys_index', app()->getLocale())->with('danger', Lang::get('Something goes wrong while creating Survey!!') . ' : ' . $exception->getMessage());
            $this->resetFields();
        }
    }

    public function edit($id)
    {
        $Survey = Survey::findOrFail($id);
        $this->name = $Survey->name;
        $this->description = $Survey->description;
        $this->idSurvey = $Survey->id;
        $this->enabled = $Survey->enabled;
        $this->archived = $Survey->archived;
        $this->updatable = $Survey->updatable;
        $this->showResult = $Survey->showResult;
        $this->showAchievement = $Survey->showAchievement;
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
                    'archived' => $this->archived,
                    'updatable' => $this->updatable,
                    'showResult' => $this->showResult,
                    'showAchievement' => $this->showAchievement,
                ]);

            return redirect()->route('surveys_index', app()->getLocale())->with('success', Lang::get('Survey Updated Successfully!!'));
        } catch (\Exception $exception) {
            $this->cancel();
            return redirect()->route('surveys_index', app()->getLocale())->with('danger', Lang::get('Something goes wrong while updating Survey!!') . ' : ' . $exception->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            Survey::find($id)->delete();
            session()->flash('success', "Survey Deleted Successfully!!");
        } catch (\Exception $e) {
            session()->flash('error', "Something goes wrong while deleting Survey!!");
        }
    }


    public function render(Request $request)
    {
        $idServey = $request->input('idServey');
        if (!is_null($idServey)) {
            $this->edit($idServey);
        }
        return view('livewire.survey-create-update')->extends('layouts.master')->section('content');
    }
}
