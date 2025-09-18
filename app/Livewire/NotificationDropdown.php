<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class NotificationDropdown extends Component
{
    public $notifications = [];
    public $latests;
    public $unreadNotificationsNumber;
    protected $listeners = ['notificationUpdated' => 'loadNotifications'];

    public function mount()
    {
        $this->loadNotifications();
    }

    public function loadNotifications()
    {
        $this->notifications = Auth::user()->notifications()->latest()->take(5)->get();
        $this->latests = Auth::user()->notifications()->latest()->count();
        $this->unreadNotificationsNumber = auth()->user()->unreadNotifications()->count();
    }

    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->find($id);
        if ($notification) {
            $notification->markAsRead();
            $this->loadNotifications();
            $this->dispatch('notificationUpdated');
        }
    }

    public function markThemAllRead()
    {
        $notifications = Auth::user()->notifications()->get();
        if ($notifications) {
            $notifications->markAsRead();
            $this->loadNotifications();
            $this->dispatch('notificationUpdated');
        }
    }

    public function render()
    {
        return view('livewire.notification-dropdown');
    }
}
