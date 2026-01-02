<?php

namespace App\Livewire;

use App\Enums\StatusRequest;
use App\Models\User;
use App\Models\UserCurrentBalanceVertical;
use App\Models\vip;
use App\Services\Balances\Balances;
use Core\Models\metta_user;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Route;
use Livewire\Component;

class UserDetails extends Component
{
    public $userProfileImage;
    public $userNationalFrontImage;
    public $userNationalBackImage;
    public $userInternationalImage;
    public $activeUser = false;

    public function mount($idUser, Request $request)
    {
        $user = User::where('idUser', Route::current()->parameter('idUser'))->first();
        if (!$user) {
            $user = User::find(Route::current()->parameter('idUser'));
            if (!$user) {
                throw new \Exception('User not found');
            }
        }

        $this->userProfileImage = User::getUserProfileImage($user->idUser);
        $this->userNationalFrontImage = User::getNationalFrontImage($user->idUser);
        $this->userNationalBackImage = User::getNationalBackImage($user->idUser);
        $this->userInternationalImage = User::getInternational($user->idUser);
        $this->idUser = $user->id;
    }

    public function render()
    {
        $params['user'] = User::find($this->idUser);
        $params['metta'] = metta_user::where('idUser', $params['user']->idUser)->first();
        $params['dispalyedUserCred'] = getUserDisplayedName($params['user']->idUser);
        if ($params['user']->status >= StatusRequest::OptValidated->value) {
            $this->activeUser = true;
            $params['userCurrentBalanceHorisontal'] = Balances::getStoredUserBalances($params['user']->idUser);
            $params['userCurrentBalanceVertical'] = UserCurrentBalanceVertical::where('user_id', $params['user']->idUser)->get();
        }
        $hasVip = vip::Where('idUser', '=', $params['user']->idUser)
            ->where('closed', '=', false)->get();
        if ($hasVip->isNotEmpty()) {
            $dateStart = new \DateTime($hasVip->first()->dateFNS);
            $dateEnd = $dateStart->modify($hasVip->first()->flashDeadline . ' hour');;
            if ($dateEnd > now()) {
                $params['vipMessage'] = Lang::get('Acctually is vip');
                $params['vip'] = vip::where('idUser', $params['user']->idUser)->first();
            } else {
                $params['vipMessage'] = Lang::get('It was a vip');
                $params['vip'] = vip::where('idUser', $params['user']->idUser)->first();
            }
        }

        return view('livewire.user-details', $params)->extends('layouts.master')->section('content');
    }
}
