<?php

namespace App\Http\Livewire;

use App\Models\Group;
use App\Models\Target;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Livewire\Component;

class GroupCreateUpdate extends Component
{
    public $idTarget, $idGroup;
    public $operator = '&&';
    public $operands = [
        ['name' => '&&', 'value' => '&&'],
        ['name' => '||', 'value' => '||'],
    ];
    public $update = false;

    protected $rules = ['operator' => 'required'];

    public function mount($idTarget, Request $request)
    {
        $this->idTarget = Route::current()->parameter('idTarget');
        $idGroup = $request->input('idGroup');
        if (!is_null($idGroup)) {
            $this->edit($idGroup);
        }
    }

    public function cancel()
    {
        return redirect()->route('target_show', ['locale' => app()->getLocale(), 'idTarget' => $this->idTarget])->with('warning', Lang::get('Group operation cancelled'));
    }

    public function edit($idGroup)
    {
        $question = Group::findOrFail($idGroup);
        $this->idGroup = $idGroup;
        $this->operator = $question->operator;
        $this->update = true;
    }


    public function update()
    {
        $this->validate();
        try {
            Group::where('id', $this->idGroup)
                ->update(['operator' => $this->operator, 'target_id' => $this->idTarget]);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->route('target_show', ['locale' => app()->getLocale(), 'idTarget' => $this->idTarget])->with('danger', Lang::get('Something goes wrong while updating Group!!') );
        }
        return redirect()->route('target_show', ['locale' => app()->getLocale(), 'idTarget' => $this->idTarget])->with('success', Lang::get('Group Updated Successfully'));

    }

    public function store()
    {
        $this->validate();
        try {
            $condition = Group::create(['operator' => $this->operator, 'target_id' => $this->idTarget]);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->route('target_show', ['locale' => app()->getLocale(), 'idTarget' => $this->idTarget])->with('danger', Lang::get('Something goes wrong while creating Group!!') );
        }
        return redirect()->route('target_show', ['locale' => app()->getLocale(), 'idTarget' => $this->idTarget])->with('success', Lang::get('Group Created Successfully!!') . ' ' . $condition->content);
    }


    public function render()
    {
        $params = ['target' => Target::find($this->idTarget)];
        return view('livewire.group-create-update', $params)->extends('layouts.master')->section('content');
    }
}
