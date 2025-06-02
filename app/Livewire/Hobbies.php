<?php

namespace App\Livewire;

use Core\Models\hobbie;
use Core\Services\settingsManager;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Hobbies extends Component
{
    public $hobbies;
    protected $rules = ['hobbies.*.selected' => 'required'];
    protected $listeners = ['save' => 'save'];

    public function render()
    {

        return view('livewire.hobbies')->extends('layouts.master')->section('content');
    }
}
