<?php

namespace App\Livewire;

use App\Models\EntityRole;
use App\Models\Partner;
use App\Models\User;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;

class PartnerEntityRoleManager extends Component
{
    use WithPagination;

    public $partnerId;
    public $partner;

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

    public function mount($partnerId)
    {
        $this->partnerId = $partnerId;
        $this->partner = Partner::findOrFail($partnerId);
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
            EntityRole::create([
                'name' => $this->newRoleName,
                'roleable_id' => $this->partnerId,
                'roleable_type' => Partner::class,
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
        $role = EntityRole::findOrFail($roleId);
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
            $role = EntityRole::findOrFail($this->editingRoleId);
            $role->update([
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
            EntityRole::findOrFail($roleId)->delete();
            session()->flash('success', Lang::get('Role revoked successfully'));
            $this->dispatch('refreshComponent');
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            session()->flash('danger', Lang::get('Failed to revoke role'));
        }
    }

    public function getSearchedUsersProperty()
    {
        if (empty($this->userSearch)) {
            return collect();
        }

        return User::where('name', 'like', '%' . $this->userSearch . '%')
            ->orWhere('email', 'like', '%' . $this->userSearch . '%')
            ->limit(10)
            ->get();
    }

    public function render()
    {
        $roles = EntityRole::where('roleable_id', $this->partnerId)
            ->where('roleable_type', Partner::class)
            ->with(['user', 'creator', 'updater'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.partner-entity-role-manager', [
            'roles' => $roles,
        ])->extends('layouts.master')->section('content');
    }
}
