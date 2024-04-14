<?php

namespace App\Http\Livewire;

use Core\Models\UserContact;
use Core\Services\settingsManager;
use Core\Services\TransactionManager;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
class Contacts extends Component
{

   
    public function render(settingsManager $settingsManager)
    {
        $userAuth = $settingsManager->getAuthUser();
        if(!$userAuth) abort(404);
        $contactUser = DB::table('user_contacts1 as user_contacts')
        ->join('users as u', 'user_contacts.idContact', '=', 'u.idUser')
    ->join('countries as c', 'u.idCountry', '=', 'c.id')
    ->where('user_contacts.idUser', $userAuth->idUser)
    ->select('user_contacts.id', 'user_contacts.name', 'user_contacts.lastName', 'u.mobile', 'u.availablity', 'c.apha2',
        DB::raw("CASE WHEN u.status = -2 THEN 'bg-warning-subtle text-warning' ELSE 'bg-success-subtle text-success' END AS color"),
        DB::raw("CASE WHEN u.status = -2 THEN 'Pending' ELSE 'User' END AS status"))
    ->get();
//dd($contactUser);
        return view('livewire.contacts',[
            'contactUser'=>$contactUser
        ])->extends('layouts.master')->section('content');
    }

    
}
