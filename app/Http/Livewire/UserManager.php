<?php

namespace App\Http\Livewire;

use Core\Services\settingsManager;
use Livewire\Component;

class UserManager extends Component
{
    protected $listeners = [
        'deleteUser' => 'deleteUser'
    ];

    public function render()
    {
        return view('livewire.user-manager')->extends('layouts.master')->section('content');
    }

    public function deleteUser($idUser, settingsManager $settingsManager)
    {
        $userAuth = $settingsManager->getAuthUser();
        if ($userAuth->idUser == $idUser) return;
        $settingsManager->deleteUser($idUser);
        return redirect()->route('user_manager',app()->getLocale());
    }

}
