<?php

namespace App\Livewire;

use App\Services\Targeting\GroupService;
use App\Services\Targeting\TargetService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Route;
use Livewire\Component;

class GroupCreateUpdate extends Component
{
    protected GroupService $groupService;
    protected TargetService $targetService;

    public $idTarget, $idGroup;
    public $operator = '&&';
    public $operands = [
        ['name' => '&&', 'value' => '&&'],
        ['name' => '||', 'value' => '||'],
    ];
    public $update = false;

    protected $rules = ['operator' => 'required'];

    public function boot(GroupService $groupService, TargetService $targetService)
    {
        $this->groupService = $groupService;
        $this->targetService = $targetService;
    }

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
        return redirect()->route('target_show', ['locale' => app()->getLocale(), 'idTarget' => $this->idTarget])
            ->with('warning', Lang::get('Group operation canceled'));
    }

    public function edit($idGroup)
    {
        $question = $this->groupService->getByIdOrFail($idGroup);
        $this->idGroup = $idGroup;
        $this->operator = $question->operator;
        $this->update = true;
    }

    public function updateGroup()
    {
        $this->validate();

        $success = $this->groupService->update(
            $this->idGroup,
            [
                'operator' => $this->operator,
                'target_id' => $this->idTarget
            ]
        );

        if (!$success) {
            return redirect()->route('target_show', ['locale' => app()->getLocale(), 'idTarget' => $this->idTarget])
                ->with('danger', Lang::get('Something goes wrong while updating Group!!'));
        }

        return redirect()->route('target_show', ['locale' => app()->getLocale(), 'idTarget' => $this->idTarget])
            ->with('success', Lang::get('Group Updated Successfully'));
    }

    public function store()
    {
        $this->validate();

        $result = $this->groupService->create([
            'operator' => $this->operator,
            'target_id' => $this->idTarget
        ]);

        if (!$result) {
            return redirect()->route('target_show', ['locale' => app()->getLocale(), 'idTarget' => $this->idTarget])
                ->with('danger', Lang::get('Something goes wrong while creating Group!!'));
        }

        return redirect()->route('target_show', ['locale' => app()->getLocale(), 'idTarget' => $this->idTarget])
            ->with('success', Lang::get('Group Created Successfully!!'));
    }

    public function render()
    {
        $params = ['target' => $this->targetService->getById($this->idTarget)];
        return view('livewire.group-create-update', $params)->extends('layouts.master')->section('content');
    }
}
