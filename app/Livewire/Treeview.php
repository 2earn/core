<?php

namespace App\Livewire;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class treeview extends Component
{


    public function render()
    {
        $results =  DB::select("select * from list_sms where iduser=197604279");

        return view('class Treeview', ['results' => $results])->extends('layouts.master')->section('content');
    }
}
