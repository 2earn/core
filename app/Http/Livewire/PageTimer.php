<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Vite;

class PageTimer extends Component
{
    public $timeRemaining;
    public $imagePath;

    public function mount($deadline = null)
    {
        $now = new \DateTime();

        if ($deadline) {
            $targetDate = \DateTime::createFromFormat('d/m/Y', $deadline);
        } else {
            $targetDate = (clone $now)->modify('+180 days');
        }

        $this->timeRemaining = max(0, $targetDate->getTimestamp() - $now->getTimestamp());
        $this->updateImagePath();
    }

    public function render()
    {
        return view('livewire.page-timer')->extends('layouts.master')->section('content');
    }

    public function decrementTime()
    {
        if ($this->timeRemaining > 0) {
            $this->timeRemaining--;
            $this->updateImagePath();
        }
    }

    public function getDaysProperty()
    {
        return floor($this->timeRemaining / 86400);
    }

    public function getHoursProperty()
    {
        return floor(($this->timeRemaining % 86400) / 3600);
    }

    public function getMinutesProperty()
    {
        return floor(($this->timeRemaining % 3600) / 60);
    }

    public function getSecondsProperty()
    {
        return $this->timeRemaining % 60;
    }

    public function resetCountdown()
    {
        $this->timeRemaining = 0;
    }

    protected $listeners = ['decrementTime'];

    private function updateImagePath()
    {
        $daysLeft = $this->days;

        if ($daysLeft <= 0) {
            $imageName = '18.png';
        } else {
            $imageIndex = ceil($daysLeft / 10);
            $imageIndex = max(1, min($imageIndex, 18));
            $imageName = "{$imageIndex}.png";
        }

        $this->imagePath = Vite::asset('resources/images/timer/' . $imageName);
    }
}
