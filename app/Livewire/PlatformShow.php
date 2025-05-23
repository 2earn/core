<?php

namespace App\Livewire;

use Core\Models\Platform;
use Illuminate\Support\Facades\Route;
use Livewire\Component;

class PlatformShow extends Component
{
    public $idPlatform;

    public function mount($id)
    {
        $this->idPlatform = $id;
        $this->currentRouteName = Route::currentRouteName();
        $platform = Platform::FindOrFail($this->idPlatform);
        if (!$platform->enabled) {
            return redirect()->route('platform_index', ['locale' => app()->getLocale()])->with('danger', trans('Platform disabled'));
        }
    }

    public function render()
    {
        $params['platform'] = Platform::FindOrFail($this->idPlatform);
        return view('livewire.platform-show', $params)->extends('layouts.master')->section('content');
    }
}
