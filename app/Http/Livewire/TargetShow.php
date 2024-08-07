<?php

namespace App\Http\Livewire;

use App\Models\Condition;
use App\Models\Target;
use App\Models\TargetGroup;
use Illuminate\Support\Facades\Lang;
use Livewire\Component;

class TargetShow extends Component
{
    public $idSurvey;

    public function mount($idTarget)
    {
        $this->idTarget = $idTarget;
    }

    public function removeCondition($idCondition)
    {
        Condition::findOrFail($idCondition)->delete();
        return redirect()->route('target_show', ['locale' => app()->getLocale(), 'idTarget' => $this->idTarget])->with('success', Lang::get('Condition Deleted Successfully!!'));
    }

    public function removeGroup($idGroup)
    {
        TargetGroup::findOrFail($idGroup)->delete();
        return redirect()->route('target_show', ['locale' => app()->getLocale(), 'idTarget' => $this->idTarget])->with('success', Lang::get('Group Deleted Successfully!!'));
    }

    public function render()
    {
        $params['target'] = Target::FindOrFail($this->idTarget);
        return view('livewire.target-show', $params)->extends('layouts.master')->section('content');
    }
}
