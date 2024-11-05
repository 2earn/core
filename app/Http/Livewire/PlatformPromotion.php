<?php

namespace App\Http\Livewire;

use App\Models\User;
use Core\Enum\Promotion;
use Core\Models\Platform;
use Illuminate\Support\Facades\Lang;
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

    public function revoqueRole($platformId, $type)
    {
        $platform = Platform::find($platformId);

        try {

            if ($type == Promotion::Financial->value) {
                $platform->financial_manager_id = null;
                $role = Promotion::Financial->name;
            }

            if ($type == Promotion::Administrative->value) {
                $platform->financial_manager_id = null;
                $role = Promotion::Administrative->name;
            }

            $platform->save();
        } catch
        (\Exception $exception) {
            return redirect()->route('platform_promotion', $this->rediredtionParams)->with('error', Lang::get($role) . self::SEPARATOR . Lang::get('Role revoque failed') . self::SEPARATOR . $exception->getMessage());

        }
        return redirect()->route('platform_promotion', $this->rediredtionParams)->with('success', Lang::get($role) . self::SEPARATOR . Lang::get('Role revoque to') . self::SEPARATOR);
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

            if ($type == Promotion::Administrative->value) {
                $platform->administrative_manager_id = $user->id;
                $role = Promotion::Administrative->name;
            }

            $platform->save();
        } catch
        (\Exception $exception) {
            return redirect()->route('platform_promotion', $this->rediredtionParams)->with('error', Lang::get($role) . self::SEPARATOR . Lang::get('manager promotion failed') . self::SEPARATOR . $exception->getMessage());

        }
        return redirect()->route('platform_promotion', $this->rediredtionParams)->with('success', Lang::get($role) . self::SEPARATOR . Lang::get('manager promotion to') . self::SEPARATOR . getUserDisplayedName($user->idUser));
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
