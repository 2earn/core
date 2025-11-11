<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;

class WelcomeMessage extends Component
{
    public $userName;
    public $greeting;
    public $greetingIcon;
    public $currentDate;
    public $userProfileImage;

    public function mount()
    {
        $this->userProfileImage = User::getUserProfileImage(auth()->user()->idUser);

        $this->userName = getUserDisplayedName(auth()->user()->idUser);

        // Set current date
        $this->currentDate = now()->format('d M, Y');

        // Determine greeting based on time of day
        $hour = date('H');
        if ($hour < 12) {
            $this->greeting = __('Good Morning');
            $this->greetingIcon = 'ri-sun-line';
        } elseif ($hour < 18) {
            $this->greeting = __('Good Afternoon');
            $this->greetingIcon = 'ri-sun-foggy-line';
        } else {
            $this->greeting = __('Good Evening');
            $this->greetingIcon = 'ri-moon-line';
        }
    }

    public function render()
    {
        return view('livewire.welcome-message');
    }
}

