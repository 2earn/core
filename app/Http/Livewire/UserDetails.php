<?php

namespace App\Http\Livewire;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Livewire\Component;
use App\Models\User;

class UserDetails extends Component
{
    public function mount($idUser, Request $request)
    {
        $this->idUser = Route::current()->parameter('idUser');
    }


    public function render()
    {
        $params['user'] = User::find($this->idUser);
        $params['dispalyedUserCred'] = getUserDisplayedName( $params['user']->idUser);;
        return view('livewire.user-details', $params)->extends('layouts.master')->section('content');
    }
}
