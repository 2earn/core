<?php

namespace App\Http\Livewire;

use App\Models\Condition;
use App\Models\Group;
use App\Models\Target;
use App\Services\Targeting\Targeting;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Route;
use Livewire\Component;

class TargetShow extends Component
{
    public $idSurvey;
    public $currentRouteName;

    public function mount($idTarget)
    {
        $this->idTarget = $idTarget;
        $this->currentRouteName = Route::currentRouteName();
    }

    public function removeCondition($idCondition, $idTarget)
    {
        Condition::findOrFail($idCondition)->delete();
        return redirect()->route('target_show', ['locale' => app()->getLocale(), 'idTarget' => $idTarget])->with('success', Lang::get('Condition Deleted Successfully'));
    }

    public function removeGroup($idGroup, $idTarget)
    {
        Group::findOrFail($idGroup)->delete();
        return redirect()->route('target_show', ['locale' => app()->getLocale(), 'idTarget' => $idTarget])->with('success', Lang::get('Group Deleted Successfully'));
    }

    public function deleteTarget($idTarget)
    {
        Target::findOrFail($idTarget)->delete();
        return redirect()->route('target_index', ['locale' => app()->getLocale()])->with('success', Lang::get('Target Deleted Successfully'));
    }


    private function getNewBindings($bindings)
    {
        $newBindings = [];
        foreach ($bindings as $bind) {
            $newBindings[] = "'" . $bind . "'";
        }
        return $newBindings;
    }

    public function render()
    {
        $params['target'] = Target::FindOrFail($this->idTarget);
        $userQuery = Targeting::getTargetQuery($params['target'], false);
        $params['sql'] = str_replace_array('?', $this->getNewBindings($userQuery->getBindings()), $userQuery->toSql());

        return view('livewire.target-show', $params)->extends('layouts.master')->section('content');
    }
}
