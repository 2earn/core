<?php

namespace App\Livewire;

use App\Enums\StatusRequest;
use App\Models\User;
use App\Services\Balances\Balances;
use App\Services\MettaUsersService;
use App\Services\UserService;
use App\Services\UserBalances\UserCurrentBalanceVerticalService;
use App\Services\VipService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Route;
use Livewire\Component;

class UserDetails extends Component
{
    public $idUser;
    public $userProfileImage;
    public $userNationalFrontImage;
    public $userNationalBackImage;
    public $userInternationalImage;
    public $activeUser = false;

    protected VipService $vipService;
    protected UserService $userService;
    protected MettaUsersService $mettaUsersService;
    protected UserCurrentBalanceVerticalService $balanceVerticalService;

    public function boot(
        VipService $vipService,
        UserService $userService,
        MettaUsersService $mettaUsersService,
        UserCurrentBalanceVerticalService $balanceVerticalService
    ) {
        $this->vipService = $vipService;
        $this->userService = $userService;
        $this->mettaUsersService = $mettaUsersService;
        $this->balanceVerticalService = $balanceVerticalService;
    }

    public function mount($idUser, Request $request)
    {
        $routeIdUser = Route::current()->parameter('idUser');
        $user = $this->userService->getUserByIdUser($routeIdUser);

        if (!$user) {
            $user = $this->userService->findById($routeIdUser);
            if (!$user) {
                throw new \Exception('User not found');
            }
        }

        $this->userProfileImage = $this->userService->getUserProfileImage($user->idUser);
        $this->userNationalFrontImage = $this->userService->getNationalFrontImage($user->idUser);
        $this->userNationalBackImage = $this->userService->getNationalBackImage($user->idUser);
        $this->userInternationalImage = $this->userService->getInternationalImage($user->idUser);
        $this->idUser = $user->id;
    }

    public function render()
    {
        $params['user'] = $this->userService->findById($this->idUser);
        $params['metta'] = $this->mettaUsersService->getMettaUser($params['user']->idUser);
        $params['dispalyedUserCred'] = getUserDisplayedName($params['user']->idUser);

        if ($params['user']->status >= StatusRequest::OptValidated->value) {
            $this->activeUser = true;
            $params['userCurrentBalanceHorisontal'] = Balances::getStoredUserBalances($params['user']->idUser);
            $params['userCurrentBalanceVertical'] = $this->balanceVerticalService->getAllUserBalances($params['user']->idUser);
        }

        $vipStatus = $this->vipService->getVipStatusForUser($params['user']->idUser);
        if ($vipStatus) {
            $params['vipMessage'] = Lang::get($vipStatus['message']);
            $params['vip'] = $vipStatus['vip'];
        }

        return view('livewire.user-details', $params)->extends('layouts.master')->section('content');
    }
}
