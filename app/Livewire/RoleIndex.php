<?php

namespace App\Livewire;

use App\Services\Role\RoleService;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Livewire\Component;
use Livewire\WithPagination;

class RoleIndex extends Component
{
    use WithPagination;

    const PAGE_SIZE = 10;
    public $search = '';
    public $currentRouteName;
    protected $paginationTheme = 'bootstrap';
    public $listeners = ['delete' => 'delete'];

    protected RoleService $roleService;

    public function boot(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    public function mount()
    {
        $this->currentRouteName = Route::currentRouteName();
    }

    public function resetPage($pageName = 'page')
    {
        $this->setPage(1, $pageName);
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function delete($id)
    {
        try {
            $this->roleService->delete($id);
            return redirect()->route('role_index', ['locale' => app()->getLocale()])->with('success', Lang::get('Role Deleted Successfully'));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->route('role_index', ['locale' => app()->getLocale()])->with('danger', Lang::get('This Role cant be Deleted !'));
        }
    }

    public function render()
    {
        $params['roles'] = $this->roleService->getPaginated($this->search, self::PAGE_SIZE);
        return view('livewire.role-index', $params)->extends('layouts.master')->section('content');
    }
}
