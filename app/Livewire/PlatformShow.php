<?php

namespace App\Livewire;

use App\Services\EntityRole\EntityRoleService;
use App\Services\Platform\PlatformService;
use Illuminate\Support\Facades\Route;
use Livewire\Component;

class PlatformShow extends Component
{
    public $idPlatform;
    public $currentRouteName;
    public $userRoles = [];
    public $canManagePlatform = false;

    protected PlatformService $platformService;
    protected EntityRoleService $entityRoleService;

    public function boot(PlatformService $platformService, EntityRoleService $entityRoleService)
    {
        $this->platformService = $platformService;
        $this->entityRoleService = $entityRoleService;
    }

    public function mount($id)
    {
        $this->idPlatform = $id;
        $this->currentRouteName = Route::currentRouteName();

        if (!$this->platformService->isPlatformEnabled($this->idPlatform)) {
            $this->redirect(route('platform_index', ['locale' => app()->getLocale()]), navigate: true);
        }

        // Load user roles for this platform
        if (auth()->check()) {
            $this->userRoles = $this->entityRoleService->getUserRolesForPlatform(
                auth()->id(),
                $this->idPlatform
            );

            // Check if user can manage this platform (has owner or manager role)
            $this->canManagePlatform = !empty(array_intersect(
                $this->userRoles,
                ['owner', 'marketing_manager', 'financial_manager']
            ));
        }
    }

    public function render()
    {
        $platform = $this->platformService->getPlatformForShow($this->idPlatform);

        if (!$platform) {
            abort(404);
        }

        // Load EntityRoles for the platform using service
        $platform->entityRoles = $this->entityRoleService->getEntityRolesKeyedByName($platform->id);

        return view('livewire.platform-show', ['platform' => $platform])->extends('layouts.master')->section('content');
    }

    /**
     * Check if the current user has a specific role for this platform
     */
    public function hasRole(string $roleName): bool
    {
        return in_array($roleName, $this->userRoles);
    }

    /**
     * Check if the current user is the platform owner
     */
    public function isOwner(): bool
    {
        return $this->hasRole('owner');
    }

    /**
     * Check if the current user is a marketing manager
     */
    public function isMarketingManager(): bool
    {
        return $this->hasRole('marketing_manager');
    }

    /**
     * Check if the current user is a financial manager
     */
    public function isFinancialManager(): bool
    {
        return $this->hasRole('financial_manager');
    }

    /**
     * Check if the current user has any management role for this platform
     */
    public function hasAnyManagementRole(): bool
    {
        return $this->canManagePlatform;
    }
}
