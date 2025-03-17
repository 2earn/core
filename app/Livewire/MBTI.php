<?php

namespace App\Livewire;

use Livewire\Component;

class MBTI extends Component
{
    public function render()
    {
        return view('livewire.m-b-t-i')->extends('layouts.master')->section('content');
    }
}
