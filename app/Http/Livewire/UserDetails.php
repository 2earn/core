<?php

namespace App\Http\Livewire;

use App\Models\User;
use App\Models\vip;
use Core\Models\metta_user;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Route;
use Livewire\Component;

class UserDetails extends Component
{
    public function mount($idUser, Request $request)
    {
        $this->idUser = Route::current()->parameter('idUser');
    }


    public function render()
    {
        $params['user'] = User::find($this->idUser);
        $params['metta'] = metta_user::where('idUser', $params['user']->idUser)->first();
        $params['dispalyedUserCred'] = getUserDisplayedName($params['user']->idUser);

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
