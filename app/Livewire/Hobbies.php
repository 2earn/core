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

    public function save(settingsManager $settingsManager)
    {
        $options = [];
        foreach ($this->hobbies->where('selected', '1') as $hob) {
            $options[] = $hob->id;
        }
        DB::table('metta_users')
            ->where('idUser', auth()->user()->idUser)
            ->update(['interests' => json_encode($options)]);
    }

    public function render()
    {
        $this->hobbies = hobbie::all();
        foreach ($this->hobbies as $p) {
            $p->selected = 0;
            if ($p->selected()) {
                $p->selected = 1;
            }
        }
        return view('livewire.hobbies')->extends('layouts.master')->section('content');
    }
}
