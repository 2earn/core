<?php

namespace App\Http\Livewire;

use App\Models\Survey;
use Illuminate\Support\Facades\Lang;
use Livewire\Component;

class SurveyCreateUpdate extends Component
{
    public $name, $enabled, $archived, $startDate, $endDate, $participationLimit, $updatable, $showResult, $achievement, $showAchievement, $description;
    public $update = false;
    protected $listeners = [
        'deleteSurvey' => 'destroy'
    ];
    // Validation Rules
    protected $rules = [
        'name' => 'required',
        'description' => 'required',
        'enabled' => 'required',
        'archived' => 'required',
        'updatable' => 'required',
        'showResult' => 'required',
        'showAchievement' => 'required'
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
            $this->resetFields();
        } catch (\Exception $exception) {
            return redirect()->route('surveys_index', app()->getLocale())->with('danger', 'Something goes wrong while creating Survey!!');
            $this->resetFields();
        }
    }

    public function edit($id)
    {
        $Survey = Survey::findOrFail($id);
        $this->name = $Survey->name;
        $this->description = $Survey->description;
        $this->category_id = $Survey->id;
        $this->updateCategory = true;
    }

    public function cancel()
    {
        $this->update = false;
        $this->resetFields();
    }

    public function update()
    {
        $this->validate();
        try {
            Survey::find($this->id)->fill(['name' => $this->name, 'description' => $this->description])->save();
            session()->flash('success', 'Survey Updated Successfully!!');

            $this->cancel();
        } catch (\Exception $e) {
            session()->flash('error', 'Something goes wrong while updating Survey!!');
            $this->cancel();
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

    public function render()
    {
        return view('livewire.survey-create-update')->extends('layouts.master')->section('content');
    }
}
