<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Crypt;
use Livewire\Component;

class ValidateAccount extends Component
{
    public $paramIdUser ;
    public  $idjo ;
    public  function  mount()
    {
        //        dd($this->$paramIdUser) ;
    }
    public function render()
    {
        //        dd($this->idjo) ;
//        dd($this->paramIdUser);
        return view('livewire.validate-account',[
            'paramIdUser'=> $this->paramIdUser
        ])->extends('layouts.master')->section('content');
    }
}
