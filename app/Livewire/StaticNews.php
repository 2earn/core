<?php

namespace App\Livewire;

use App\Services\Settings\SettingService;
use Livewire\Component;

class StaticNews extends Component
{
    public $enableStaticNews = 0;

    protected SettingService $settingService;

    public function boot(SettingService $settingService)
    {
        $this->settingService = $settingService;
    }

    public function render()
    {
        $this->enableStaticNews = $this->settingService->getIntegerByParameterName('ENABLE_STATIC_NEWS') ?? 0;

        return view('livewire.static-news');
    }
}
