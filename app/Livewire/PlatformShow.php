<?php

namespace App\Livewire;

use App\Services\Platform\PlatformService;
use Illuminate\Support\Facades\Route;
use Livewire\Component;

class PlatformShow extends Component
{
    public $idPlatform;
    public $currentRouteName;

    protected PlatformService $platformService;

    public function boot(PlatformService $platformService)
    {
        $this->platformService = $platformService;
    }

    public function mount($id)
    {
        $this->idPlatform = $id;
        $this->currentRouteName = Route::currentRouteName();

        if (!$this->platformService->isPlatformEnabled($this->idPlatform)) {
            $this->redirect(route('platform_index', ['locale' => app()->getLocale()]), navigate: true);
        }
    }

    public function render()
    {
        $platform = $this->platformService->getPlatformForShow($this->idPlatform);

        if (!$platform) {
            abort(404);
        }

        return view('livewire.platform-show', ['platform' => $platform])->extends('layouts.master')->section('content');
    }
}
