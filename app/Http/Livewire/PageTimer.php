<?php

namespace App\Http\Livewire;

use Livewire\Component;

class PageTimer extends Component
{
    public $timeRemaining;

    public function mount()
    {
        // Initialize with zero time
        $this->timeRemaining = 0;
    }

    public function render()
    {
        return view('livewire.page-timer');
    }

    public function decrementTime()
    {
        if ($this->timeRemaining > 0) {
            $this->timeRemaining--;
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
}
