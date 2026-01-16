<?php

namespace App\Livewire;

use App\Models\Platform;
use App\Models\User;
use App\Services\EntityRole\EntityRoleService;
use App\Services\UserService;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;

class PlatformEntityRoleManager extends Component
{
    use WithPagination;

    public $platformId;
    public $platform;

    // For adding new role
    public $newRoleName = '';
    public $newRoleUserId = '';
    public $userSearch = '';
    public $showUserDropdown = false;

    // For editing existing role
    public $editingRoleId = null;
    public $editRoleName = '';
    public $editRoleUserId = '';

    public $listeners = ['refreshComponent' => '$refresh'];

    protected $entityRoleService;
    protected $userService;

    public function boot(EntityRoleService $entityRoleService, UserService $userService)
    {
        $this->entityRoleService = $entityRoleService;
        $this->userService = $userService;
    }

    public function mount($platformId)
    {
        $this->platformId = $platformId;
        $this->platform = Platform::findOrFail($platformId);
    }

    public function updatedUserSearch()
    {
        $this->showUserDropdown = !empty($this->userSearch);
    }

    public function selectUser($userId)
    {
        if ($this->editingRoleId) {
            $this->editRoleUserId = $userId;
        } else {
            $this->newRoleUserId = $userId;
        }
        $this->userSearch = '';
        $this->showUserDropdown = false;
    }

    public function addRole()
    {
        $this->validate([
            'newRoleName' => 'required|string|max:255',
            'newRoleUserId' => 'nullable|exists:users,id',
        ], [
            'newRoleName.required' => Lang::get('Role name is required'),
            'newRoleUserId.exists' => Lang::get('Selected user does not exist'),
        ]);

        try {
            $this->entityRoleService->createPlatformRole($this->platformId, [
                'name' => $this->newRoleName,
                'user_id' => $this->newRoleUserId ?: null,
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
            ]);

            $this->reset(['newRoleName', 'newRoleUserId', 'userSearch']);
            session()->flash('success', Lang::get('Role added successfully'));
            $this->dispatch('refreshComponent');
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            session()->flash('danger', Lang::get('Failed to add role'));
        }
    }

    public function editRole($roleId)
    {
        $role = $this->entityRoleService->getRoleById($roleId);
        $this->editingRoleId = $roleId;
        $this->editRoleName = $role->name;
        $this->editRoleUserId = $role->user_id;
    }

    public function updateRole()
    {
        $this->validate([
            'editRoleName' => 'required|string|max:255',
            'editRoleUserId' => 'nullable|exists:users,id',
        ], [
            'editRoleName.required' => Lang::get('Role name is required'),
            'editRoleUserId.exists' => Lang::get('Selected user does not exist'),
        ]);

        try {
            $this->entityRoleService->updateRole($this->editingRoleId, [
                'name' => $this->editRoleName,
                'user_id' => $this->editRoleUserId ?: null,
                'updated_by' => auth()->id(),
            ]);

            $this->reset(['editingRoleId', 'editRoleName', 'editRoleUserId', 'userSearch']);
            session()->flash('success', Lang::get('Role updated successfully'));
            $this->dispatch('refreshComponent');
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            session()->flash('danger', Lang::get('Failed to update role'));
        }
    }

    public function cancelEdit()
    {
        $this->reset(['editingRoleId', 'editRoleName', 'editRoleUserId', 'userSearch']);
    }

    public function revokeRole($roleId)
    {
        try {
            $this->entityRoleService->deleteRole($roleId);
            session()->flash('success', Lang::get('Role revoked successfully'));
            $this->dispatch('refreshComponent');
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            session()->flash('danger', Lang::get('Failed to revoke role'));
        }
    }

    public function getSearchedUsersProperty()
    {
        return $this->userService->searchUsers($this->userSearch, 10);
    }

    public function render()
    {
        $roles = $this->entityRoleService->getRolesForPlatform($this->platformId, true, 10);

        return view('livewire.platform-entity-role-manager', [
            'roles' => $roles,
        ])->extends('layouts.master')->section('content');
    }
}
