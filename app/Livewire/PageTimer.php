<?php

namespace App\Livewire;

use Datetime;
use App\Services\Settings\SettingService;
use Illuminate\Support\Facades\Vite;
use Livewire\Component;

class PageTimer extends Component
{
    const FROM_DATE = "2024/10/10";

    protected SettingService $settingService;

    public $timeRemaining;
    public $imagePath;
    public $targetDate;
    public $passedDays = 0;
    protected $listeners = ['decrementTime' => 'decrementTime'];

    public function boot(SettingService $settingService)
    {
        $this->settingService = $settingService;
    }

    public function mount($deadline = null)
    {
        if (!is_null($deadline)) {
            // Use SettingService to get deadline value
            $dateValue = $this->settingService->getStringValue($deadline);
            $this->targetDate = $dateValue ? new Datetime($dateValue) : (new DateTime())->add(new \DateInterval('P30D'));
        } else {
            $this->targetDate = (new DateTime())->add(new \DateInterval('P30D'));
        }
        $this->updateTimeRemaining($this->targetDate);
    }

    private function updateTimeRemaining()
    {
        $now = new DateTime();
        $interval = $now->diff($this->targetDate);

        $this->timeRemaining = [
            'days' => $interval->days,
            'hours' => $interval->h,
            'minutes' => $interval->i,
            'seconds' => $interval->s,
        ];
        $this->updateImagePath();
    }

    private function updateImagePath()
    {
        $from = new Datetime(self::FROM_DATE);
        $now = new DateTime();
        $interval = $from->diff($this->targetDate);
        $passed = $from->diff($now);
        $this->passedDays = round($passed->days / $interval->days, 4);
        $imageName = $interval->days - $passed->days <= 0 ? '18.png' : intval($this->passedDays * 18) . ".png";
        $this->imagePath = Vite::asset('resources/images/timer/' . $imageName);
    }

    public function decrementTime()
    {
        $this->updateTimeRemaining();
        $this->updateImagePath();
    }

    public function render()
    {
        return view('livewire.page-timer')->extends('layouts.master')->section('content');
    }
}
