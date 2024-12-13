<?php

namespace App\Http\Livewire;

use App\Models\Condition;
use App\Models\Target;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Livewire\Component;

class GroupConditionCreateUpdate extends Component
{
    public $idTarget, $idGroup, $idCondition;
    public $operand, $operator, $value;

    public $update = false;

    public $operands, $operators;

    protected $rules = [
        'operand' => 'required',
        'operator' => 'required',
        'value' => 'required'
    ];

    public function mount($idGroup, Request $request)
    {
        $this->idTarget = Route::current()->parameter('idTarget');
        $this->idGroup = Route::current()->parameter('idGroup');
        $idCondition = $request->input('idCondition');
        if (!is_null($idCondition)) {
            $this->edit($idCondition);
        }
    }

    public function edit($idCondition)
    {
        $question = Condition::findOrFail($idCondition);
        $this->idCondition = $idCondition;
        $this->operand = $question->operand;
        $this->operator = $question->operator;
        $this->value = $question->value;
        $this->update = true;
    }

    public function cancel()
    {
        return redirect()->route('surveys_show', ['locale' => app()->getLocale(), 'idSurvey' => $this->idSurvey])->with('warning', Lang::get('Condition operation cancelled'));
    }

    public function update()
    {
        $this->validate();
        try {
            Condition::where('id', $this->idCondition)
                ->update([
                    'operand' => $this->operand,
                    'operator' => $this->operator,
                    'value' => $this->value,
                    'target_group_id' => $this->idGroup,
                ]);
        } catch (\Exception $exception) {
            $this->cancel();
            Log::error($exception->getMessage());
            return redirect()->route('target_show', ['locale' => app()->getLocale(), 'idTarget' => $this->idTarget])->with('danger', Lang::get('Something goes wrong while updating Condition!!') );
        }
        return redirect()->route('target_show', ['locale' => app()->getLocale(), 'idTarget' => $this->idTarget])->with('success', Lang::get('Condition Updated Successfully'));
    }

    public function store()
    {
        $this->validate();
        try {
            Condition::create(['operand' => $this->operand, 'operator' => $this->operator, 'value' => $this->value, 'target_group_id' => $this->idGroup]);

        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->route('target_show', ['locale' => app()->getLocale(), 'idTarget' => $this->idTarget])->with('danger', Lang::get('Something goes wrong while creating Condition!!') );
        }
        return redirect()->route('target_show', ['locale' => app()->getLocale(), 'idTarget' => $this->idTarget])->with('success', Lang::get('Condition Created Successfully'));
    }

    public function render()
    {
        $this->operators = Condition::$operators;
        $this->operands = Condition::operands();
        $params = ['target' => Target::find($this->idTarget)];
        return view('livewire.condition-create-update', $params)->extends('layouts.master')->section('content');
    }
}
