<?php

namespace App\Livewire;

use App\Models\User;
use App\Services\Role\RoleService;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

class EditAdmin extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $mobile;
    public $name;
    public $userRole;
    public $allRoles = [];
    public $currentId;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function changeRole($idUser)
    {
        try {
            if ($this->userRole == "") {
                return redirect()->route('role_assign', app()->getLocale())->with('danger', Lang::get('Please choose a role'));
            }
            $user = User::find($idUser);
            $user->syncRoles($this->userRole);
            return redirect()->route('role_assign', app()->getLocale())->with('success', Lang::get('User role updated successfully'));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->route('role_assign', app()->getLocale())->with('danger', Lang::get('User role update failed'));
        }
    }


    public function edit($idUser)
    {
        $this->allRoles = Role::all();
        $user = User::find($idUser);
        if (!$user->hasAnyRole(Role::all())) {
            $user->syncRoles('user');
        }
        $this->userRole = $user->getRoleNames()[0];
        $this->name =getUserDisplayedName($user->idUser);
        $this->mobile = $user->mobile;
        $this->currentId = $user->id;
    }
    public function render()
    {
        $roleService = app(RoleService::class);
        $userRoles = $roleService->getUserRoles($this->search, 10);

        return view('livewire.edit-admin', ['userRoles' => $userRoles])->extends('layouts.master')->section('content');
    }

}
