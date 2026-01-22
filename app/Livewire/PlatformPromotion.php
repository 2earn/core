<?php

namespace App\Livewire;

use App\Enums\Promotion;
use App\Models\User;
use App\Models\Platform;
use App\Models\EntityRole;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Livewire\Component;

class PlatformPromotion extends Component
{
    const SEPARATOR = ' ';
    public $userId, $platforms, $platform, $roles, $role, $userProfileImage, $currentRouteName;
    public $rediredtionParams;

    public function mount($userId)
    {
        $this->userId = Route::current()->parameter('userId');
        $this->currentRouteName = Route::currentRouteName();
        $this->platforms = Platform::all();
        $this->rediredtionParams = [
            'locale' => app()->getLocale(),
            'userId' => $userId
        ];
    }

    /**
     * Get role name from Promotion enum type
     */
    private function getRoleNameFromType($type): array
    {
        return match($type) {
            Promotion::Financial->value => ['name' => 'financial_manager', 'label' => Promotion::Financial->name],
            Promotion::Marketing->value => ['name' => 'marketing_manager', 'label' => Promotion::Marketing->name],
            Promotion::Owner->value => ['name' => 'owner', 'label' => Promotion::Owner->name],
            default => throw new \Exception('Invalid role type'),
        };
    }

    public function revokeRole($platformId, $type)
    {
        $platform = Platform::find($platformId);

        if (!$platform) {
            return redirect()->route('platform_promotion', $this->rediredtionParams)
                ->with('danger', Lang::get('Platform not found'));
        }

        try {
            $roleInfo = $this->getRoleNameFromType($type);
            $roleName = $roleInfo['name'];
            $roleLabel = $roleInfo['label'];

            // Find and delete the EntityRole
            $entityRole = EntityRole::where('roleable_type', 'App\Models\Platform')
                ->where('roleable_id', $platformId)
                ->where('name', $roleName)
                ->first();

            if ($entityRole) {
                $entityRole->delete();
                Log::info("Role revoked via EntityRole", [
                    'platform_id' => $platformId,
                    'role' => $roleName
                ]);
            } else {
                Log::warning("EntityRole not found for revocation", [
                    'platform_id' => $platformId,
                    'role' => $roleName
                ]);
                return redirect()->route('platform_promotion', $this->rediredtionParams)
                    ->with('warning', Lang::get($roleLabel) . self::SEPARATOR . Lang::get('Role not found'));
            }

        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->route('platform_promotion', $this->rediredtionParams)
                ->with('danger', Lang::get($roleLabel ?? 'Role') . self::SEPARATOR . Lang::get('Role revoke failed') . self::SEPARATOR . $exception->getMessage());
        }

        return redirect()->route('platform_promotion', $this->rediredtionParams)
            ->with('success', Lang::get($roleLabel) . self::SEPARATOR . Lang::get('Role revoked'));
    }


    public function grantRole($userId, $platformId, $type)
    {
        $user = User::find($userId);
        $platform = Platform::find($platformId);

        if (!$user || !$platform) {
            return redirect()->route('platform_promotion', $this->rediredtionParams)
                ->with('danger', Lang::get('User or Platform not found'));
        }

        try {
            $roleInfo = $this->getRoleNameFromType($type);
            $roleName = $roleInfo['name'];
            $roleLabel = $roleInfo['label'];

            // Check if role already exists for this platform
            $existingRole = EntityRole::where('roleable_type', 'App\Models\Platform')
                ->where('roleable_id', $platformId)
                ->where('name', $roleName)
                ->first();

            if ($existingRole) {
                // Update existing role to new user
                $existingRole->user_id = $user->id;
                $existingRole->save();
                Log::info("Role updated via EntityRole", [
                    'platform_id' => $platformId,
                    'role' => $roleName,
                    'user_id' => $user->id
                ]);
            } else {
                // Create new EntityRole with role name
                EntityRole::create([
                    'user_id' => $user->id,
                    'name' => $roleName,
                    'roleable_type' => 'App\Models\Platform',
                    'roleable_id' => $platformId
                ]);
                Log::info("Role granted via EntityRole", [
                    'platform_id' => $platformId,
                    'role' => $roleName,
                    'user_id' => $user->id
                ]);
            }

        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->route('platform_promotion', $this->rediredtionParams)
                ->with('danger', Lang::get($roleLabel ?? 'Role') . self::SEPARATOR . Lang::get('Manager promotion failed') . self::SEPARATOR . $exception->getMessage());
        }

        return redirect()->route('platform_promotion', $this->rediredtionParams)
            ->with('success', Lang::get($roleLabel) . self::SEPARATOR . Lang::get('Manager promotion to') . self::SEPARATOR . getUserDisplayedName($user->idUser));
    }

    /**
     * Check if user has a specific role for a platform
     */
    public function userHasRole($userId, $platformId, $roleName): bool
    {
        return EntityRole::where('roleable_type', 'App\Models\Platform')
            ->where('roleable_id', $platformId)
            ->where('name', $roleName)
            ->where('user_id', $userId)
            ->exists();
    }

    /**
     * Get the user ID who has a specific role for a platform
     */
    public function getRoleUserId($platformId, $roleName): ?int
    {
        $entityRole = EntityRole::where('roleable_type', 'App\Models\Platform')
            ->where('roleable_id', $platformId)
            ->where('name', $roleName)
            ->first();

        return $entityRole ? $entityRole->user_id : null;
    }

    /**
     * Check if user has any role for a platform
     */
    public function userHasAnyRole($userId, $platformId): bool
    {
        return EntityRole::where('roleable_type', 'App\Models\Platform')
            ->where('roleable_id', $platformId)
            ->where('user_id', $userId)
            ->exists();
    }

    public function render()
    {
        $user = User::find($this->userId);

        // Load EntityRoles for all platforms
        foreach ($this->platforms as $platform) {
            $platform->entityRoles = EntityRole::where('roleable_type', 'App\Models\Platform')
                ->where('roleable_id', $platform->id)
                ->get()
                ->keyBy('name');
        }

        $params = [
            'platforms' => $this->platforms,
            'user' => $user,
        ];
        $this->userProfileImage = User::getUserProfileImage($user->idUser);

        return view('livewire.platform-promotion', $params)->extends('layouts.master')->section('content');
    }
}
