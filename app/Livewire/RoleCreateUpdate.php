<?php

namespace App\Livewire;

use App\Services\Role\RoleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class RoleCreateUpdate extends Component
{
    public
        $idRole,
        $name;
    protected $rules = ['name' => 'required'];
    public $update = false;

    protected RoleService $roleService;

    public function boot(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    public function mount(Request $request)
    {
        $this->idRole = $request->query('idRole');
        if (!is_null($this->idRole)) {
            $this->edit($this->idRole);
        }
    }


    public function cancel()
    {
        return redirect()->route('role_index', ['locale' => app()->getLocale()])->with('warning', Lang::get('Role operation canceled'));
    }

    public function edit($idRole)
    {
        $role = $this->roleService->getByIdOrFail($idRole);
        $this->name = $role->name;
        $this->idRole = $role->id;
        $this->update = true;
    }

    public function updateRole()
    {
        $this->validate();
        try {
            $params = [
                'name' => $this->name,
            ];
            $this->roleService->update($this->idRole, $params);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->route('role_index', ['locale' => app()->getLocale()])->with('danger', Lang::get('Something goes wrong while updating Role'));
        }
        return redirect()->route('role_index', ['locale' => app()->getLocale()])->with('success', Lang::get('Role Updated Successfully'));

    }

    public function store()
    {
        try {
            $this->validate();
            $this->roleService->create([
                'name' => $this->name,
                'guard_name' => 'web'
            ]);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->route('role_create_update', ['locale' => app()->getLocale()])->with('danger', Lang::get('Something goes wrong while creating Role!!') . ' ' . $exception->getMessage());
        }
        return redirect()->route('role_index', ['locale' => app()->getLocale()])->with('success', Lang::get('Role Created Successfully'));
    }

    public function render()
    {
        return view('livewire.role-create-update')->extends('layouts.master')->section('content');
    }

}
