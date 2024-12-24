<?php

namespace App\Http\Livewire;

use App\Jobs\TranslationFilesToDatabase;
use Livewire\Component;

class NewBalance extends Component
{
    public function render()
    {


        return view('livewire.new-balance')->extends('layouts.master')->section('content');
    }
}
