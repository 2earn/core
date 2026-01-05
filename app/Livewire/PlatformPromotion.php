<?php

namespace App\Livewire;

use App\Enums\Promotion;
use App\Models\User;
use App\Models\Platform;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Livewire\Component;

class PlatformPromotion extends Component
{
    const SEPARATOR = ' ';
    public $userId, $platforms, $platform, $roles, $role, $userProfileImage;
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

    public function revokeRole($platformId, $type)
    {
        $platform = Platform::find($platformId);
        try {

            if ($type == Promotion::Financial->value) {
                $platform->financial_manager_id = null;
                $role = Promotion::Financial->name;
            }

            if ($type == Promotion::Marketing->value) {
                $platform->marketing_manager_id = null;
                $role = Promotion::Marketing->name;
            }

            if ($type == Promotion::Owner->value) {
                $platform->owner_id = null;
                $role = Promotion::Owner->name;
            }

            $platform->save();
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->route('platform_promotion', $this->rediredtionParams)->with('danger', Lang::get($role) . self::SEPARATOR . Lang::get('Role revoque failed') . self::SEPARATOR . $exception->getMessage());
        }
        return redirect()->route('platform_promotion', $this->rediredtionParams)->with('success', Lang::get($role) . self::SEPARATOR . Lang::get('Role revoqued'));
    }


    public function grantRole($userId, $platformId, $type)
    {
        $user = User::find($userId);
        $platform = Platform::find($platformId);

        try {

            if ($type == Promotion::Financial->value) {
                $platform->financial_manager_id = $user->id;
                $role = Promotion::Financial->name;
            }

            if ($type == Promotion::Marketing->value) {
                $platform->marketing_manager_id = $user->id;
                $role = Promotion::Marketing->name;
            }

            if ($type == Promotion::Owner->value) {
                $platform->owner_id = $user->id;
                $role = Promotion::Owner->name;
            }

            $platform->save();
        } catch
        (\Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->route('platform_promotion', $this->rediredtionParams)->with('danger', Lang::get($role) . self::SEPARATOR . Lang::get('Manager promotion failed') . self::SEPARATOR . $exception->getMessage());
        }
        return redirect()->route('platform_promotion', $this->rediredtionParams)->with('success', Lang::get($role) . self::SEPARATOR . Lang::get('Manager promotion to') . self::SEPARATOR . getUserDisplayedName($user->idUser));
    }

    public function render()
    {
        $user = User::find($this->userId);
        $params = [
            'platforms' => $this->platforms,
            'user' => $user,
        ];
        $this->userProfileImage = User::getUserProfileImage($user->idUser);

        return view('livewire.platform-promotion', $params)->extends('layouts.master')->section('content');
    }
}
