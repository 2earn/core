<?php

namespace App\Http\Livewire;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class RoleCreateUpdate extends Component
{
    public
        $idRole,
        $name;
    protected $rules = ['name' => 'required'];
    public $update = false;

    public function mount(Request $request)
    {
        $this->idRole = $request->query('id');
        if (!is_null($this->idRole)) {
            $this->edit($this->idRole);
        }
    }


    public function cancel()
    {
        return redirect()->route('role_index', ['locale' => app()->getLocale()])->with('warning', Lang::get('Role operation cancelled!!'));
    }

    public function edit($idRole)
    {

        $role = Role::findOrFail($idRole);
        $this->name = $role->name;
        $this->idRole = $role->id;
        $this->update = true;
    }

    public function update()
    {
        $this->validate();
        try {
            $params = [
                'name' => $this->name,
            ];
            Role::where('id', $this->idRole)
                ->update($params);
        } catch (\Exception $exception) {
            return redirect()->route('role_index', ['locale' => app()->getLocale()])->with('danger', Lang::get('Something goes wrong while updating Role!!'));
        }
        return redirect()->route('role_index', ['locale' => app()->getLocale()])->with('success', Lang::get('Role Updated Successfully!!'));

    }

    public function store()
    {
        $latestRole = Role::orderBy('id', 'desc')->first();
        try {
            $this->validate();
            DB::insert('insert into roles ( id,name,guard_name,created_at,updated_at) values (?, ?, ?)', [$latestRole->id + 1, $this->name, 'web', now(), now()]);
        } catch (\Exception $exception) {
            dd($exception);
            return redirect()->route('role_create_update', ['locale' => app()->getLocale()])->with('danger', Lang::get('Something goes wrong while creating Role!!') . ' ' . $exception->getMessage());
        }
        return redirect()->route('role_index', ['locale' => app()->getLocale()])->with('success', Lang::get('Role Created Successfully!!'));
    }

    public function render()
    {
        return view('livewire.role-create-update')->extends('layouts.master')->section('content');
    }

}
