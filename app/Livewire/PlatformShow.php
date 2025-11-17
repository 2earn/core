<?php

namespace App\Livewire;

use Core\Models\Platform;
use Illuminate\Support\Facades\Route;
use Livewire\Component;

class PlatformShow extends Component
{
    public $idPlatform;
    public $currentRouteName;

    public function mount($id)
    {
        $this->idPlatform = $id;
        $this->currentRouteName = Route::currentRouteName();
        $platform = Platform::FindOrFail($this->idPlatform);
        if (!$platform->enabled) {
            $this->redirect(route('platform_index', ['locale' => app()->getLocale()]), navigate: true);
        }
    }

    public function render()
    {
        $platform = Platform::with(['businessSector', 'logoImage', 'deals', 'items', 'coupons'])
            ->withCount(['deals', 'items', 'coupons'])
            ->findOrFail($this->idPlatform);

        return view('livewire.platform-show', [
            'platform' => $platform
        ])->extends('layouts.master')->section('content');
    }
}
