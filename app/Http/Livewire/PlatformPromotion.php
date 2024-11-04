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
    public $userId, $platforms, $platform, $roles, $role, $userProfileImage;

    public function mount($userId)
    {
        $this->userId = Route::current()->parameter('userId');
        $this->currentRouteName = Route::currentRouteName();
        $this->platforms = Platform::all();
    }

    public function promote($userId, $platformId, $type)
    {
        $rediredtionParams = [
            'locale' => app()->getLocale(),
            'userId' => $userId
        ];

        try {
            $user = User::find($userId);
            $platform = Platform::find($platformId);

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
            return redirect()->route('platform_promotion', $rediredtionParams)->with('error', Lang::get($role) . ' ' . Lang::get('manager promotion failed') . $exception->getMessage());

        }
        return redirect()->route('platform_promotion', $rediredtionParams)->with('success', Lang::get($role) . ' ' . Lang::get('manager promotion Succede'));
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
