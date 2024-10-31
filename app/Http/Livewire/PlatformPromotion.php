<?php

namespace App\Http\Livewire;

use App\Models\User;
use Core\Models\Platform;
use Illuminate\Support\Facades\Route;
use Livewire\Component;

class PlatformPromotion extends Component
{
    public $userId, $platforms, $platform, $roles, $role,$userProfileImage;

    public function mount($userId)
    {
        $this->userId = Route::current()->parameter('userId');
        $this->currentRouteName = Route::currentRouteName();
        $this->platforms = Platform::all();
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
