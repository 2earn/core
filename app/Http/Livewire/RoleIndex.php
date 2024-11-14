<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Lang;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class RoleIndex extends Component
{
    public $listeners = ['delete' => 'delete'];

    public function delete($id)
    {
        try {
            if ($id > 4) {
                Role::findOrFail($id)->delete();

                return redirect()->route('role_index', ['locale' => app()->getLocale()])->with('success', Lang::get('Role Deleted Successfully'));
            }
            return redirect()->route('role_index', ['locale' => app()->getLocale()])->with('warning', Lang::get('This Role cant be Deleted !'));
        } catch (\Exception $exception) {
            return redirect()->route('role_index', ['locale' => app()->getLocale()])->with('error', $exception->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.role-index')->extends('layouts.master')->section('content');
    }
}
