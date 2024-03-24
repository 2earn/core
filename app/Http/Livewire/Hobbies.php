<?php

namespace App\Http\Livewire;

use Core\Services\settingsManager;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Hobbies extends Component
{
    public $hobbies;
    protected $rules = [
        'hobbies.*.selected' => 'required',
    ];
    protected $listeners=[
      'save'=>'save'
    ];
    public function render(settingsManager $settingsManager)
    {
        $this->hobbies = $settingsManager->getAllHobbies();
        foreach ($this->hobbies as $p) {
            $p->selected = 0;
            if ($p->selected() == 1) {
                $p->selected = 1;
            }
        }
        return view('livewire.hobbies')->extends('layouts.master')->section('content');
    }
    public function save(settingsManager $settingsManager)
    {
        $options = [];
        $user = $settingsManager->getAuthUser();
        foreach ($this->hobbies->where('selected', '1') as $hob) {
            $options[] = $hob->id;
        }
        DB::table('metta_users')
            ->where('idUser', $user->idUser)
            ->update(['interests' => json_encode($options)]);
//        return redirect()->route('hobbies', app()->getLocale())->with('SuccesUpdateHobbies', 'Update hobies succes');
    }
}
