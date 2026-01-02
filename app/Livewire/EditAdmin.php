<?php

namespace App\Livewire;

use App\Services\Role\RoleService;
use App\Services\UserService;
use Illuminate\Support\Facades\Lang;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

class EditAdmin extends Component
{
    use WithPagination;

    protected RoleService $roleService;
    protected UserService $userService;
    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $mobile;
    public $name;
    public $userRole;
    public $allRoles = [];
    public $currentId;

    public function boot(RoleService $roleService, UserService $userService)
    {
        $this->roleService = $roleService;
        $this->userService = $userService;
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function changeRole($idUser)
    {
        try {
            if ($this->userRole == "") {
                return redirect()->route('role_assign', app()->getLocale())
                    ->with('danger', Lang::get('Please choose a role'));
            }

            $user = $this->userService->findById($idUser);
            if (!$user) {
                return redirect()->route('role_assign', app()->getLocale())
                    ->with('danger', Lang::get('User not found'));
            }

            $user->syncRoles($this->userRole);

            return redirect()->route('role_assign', app()->getLocale())
                ->with('success', Lang::get('User role updated successfully'));
        } catch (\Exception $exception) {
            return redirect()->route('role_assign', app()->getLocale())
                ->with('danger', Lang::get('User role update failed'));
        }
    }

    public function edit($idUser)
    {
        $this->allRoles = Role::all();
        $user = $this->userService->findById($idUser);

        if (!$user) {
            return;
        }

        if (!$user->hasAnyRole(Role::all())) {
            $user->syncRoles('user');
        }

        $this->userRole = $user->getRoleNames()[0];
        $this->name = getUserDisplayedName($user->idUser);
        $this->mobile = $user->mobile;
        $this->currentId = $user->id;
    }

    public function render()
    {
        $userRoles = $this->roleService->getUserRoles($this->search, 10);

        return view('livewire.edit-admin', ['userRoles' => $userRoles])
            ->extends('layouts.master')
            ->section('content');
    }

}
