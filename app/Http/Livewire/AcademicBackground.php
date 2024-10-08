<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class AcademicBackground extends Component
{
    public $deadline;

    public function mount()
    {
        $this->deadline = DB::table('settings')->where('ParameterName', 'default_cs')->value('StringValue');
    }
    public function render()
    {
        return view('livewire.academic-background')->extends('layouts.master')->section('content');
    }
}
