<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class treeview extends Component
{


    public function render()
    {
        $results =  DB::select("select * from list_sms where iduser=197604279");

        return view('livewire.treeview', ['results' => $results])->extends('layouts.master')->section('content');
    }
}
