<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Route;
use Livewire\Component;
use Core\Models\Platform;

class PlatformPromotion extends Component
{
    public $idUser,$platforms,$platform,$roles,$role;

    public function mount()
    {
        $this->idUser =  Route::current()->parameter('idUser');;
        $this->currentRouteName = Route::currentRouteName();
        $this->platforms = Platform::all();
    }
    public function render()
    {
        $params=[
            'platforms' => $this->platforms,
        ];
        return view('livewire.platform-promotion',$params)->extends('layouts.master')->section('content');
    }
}
