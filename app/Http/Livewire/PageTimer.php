<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Illuminate\Support\Facades\Vite;
use \Datetime;

class PageTimer extends Component
{
    const DEFAULT_DATE = "09/04/2025";
    public $timeRemaining;
    public $imagePath;

    public function mount($deadline = null)
    {
        if (!is_null($deadline)) {
            $dateValue = DB::table('settings')->where('ParameterName', $deadline)->value('StringValue');
            $targetDate = $dateValue ? DateTime::createFromFormat('d/m/Y', $dateValue) : DateTime::createFromFormat('d/m/Y', self::DEFAULT_DATE);
        } else {
            $targetDate = DateTime::createFromFormat('d/m/Y', self::DEFAULT_DATE);
        }
        $this->updateTimeRemaining($targetDate);
    }

    private function updateTimeRemaining($targetDate)
    {
        $now = new DateTime();
        $interval = $now->diff($targetDate);

        $totalDays = $interval->m * 30 + $interval->d;

        $this->timeRemaining = [
            'days' => $totalDays,
            'hours' => $interval->h,
            'minutes' => $interval->i,
            'seconds' => $interval->s,
        ];
        $this->updateImagePath();
    }


    public function decrementTime()
    {
        if (
            $this->timeRemaining['days'] > 0 ||
            $this->timeRemaining['hours'] > 0 ||
            $this->timeRemaining['minutes'] > 0 ||
            $this->timeRemaining['seconds'] > 0
        ) {
            if ($this->timeRemaining['seconds'] > 0) {
                $this->timeRemaining['seconds']--;
            } else {
                if ($this->timeRemaining['minutes'] > 0) {
                    $this->timeRemaining['minutes']--;
                    $this->timeRemaining['seconds'] = 59;
                } else {
                    if ($this->timeRemaining['hours'] > 0) {
                        $this->timeRemaining['hours']--;
                        $this->timeRemaining['minutes'] = 59;
                        $this->timeRemaining['seconds'] = 59;
                    } else {
                        if ($this->timeRemaining['days'] > 0) {
                            $this->timeRemaining['days']--;
                            $this->timeRemaining['hours'] = 23;
                            $this->timeRemaining['minutes'] = 59;
                            $this->timeRemaining['seconds'] = 59;
                        }
                    }
                }
            }

            $this->updateImagePath();
        }
    }


    private function updateImagePath()
    {
        $daysLeft = $this->timeRemaining['days'];

        if ($daysLeft <= 0) {
            $imageName = '18.png';
        } else {
            $imageIndex = ceil($daysLeft / 10);
            $imageIndex = max(1, min($imageIndex, 18));
            $imageName = "{$imageIndex}.png";
        }

        $this->imagePath = Vite::asset('resources/images/timer/' . $imageName);
    }

    public function render()
    {
        return view('livewire.page-timer')->extends('layouts.master')->section('content');
    }
}
