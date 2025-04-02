<?php

namespace App\Livewire;

use Core\Models\Platform;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Route;
use Livewire\Component;

class PlatformShow extends Component
{
    public $idPlatform;

    public function mount($id)
    {
        $this->idPlatform = $id;
        $this->currentRouteName = Route::currentRouteName();
    }

    public function render()
    {
        $params['platform'] = Platform::FindOrFail($this->idPlatform);
        if ($params['platform']->show_profile) {
            return view('livewire.platform-show', $params)->extends('layouts.master')->section('content');
        }
        throw new \Exception(Lang::get('Platform show profile disabled'));

    }
}
