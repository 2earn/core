<?php

namespace App\Livewire;

use App\Services\NotificationService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class NotificationDropdown extends Component
{
    protected NotificationService $notificationService;

    public $notifications = [];
    public $latests;
    public $unreadNotificationsNumber;
    protected $listeners = ['notificationUpdated' => 'loadNotifications'];

    public function boot(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function mount()
    {
        $this->loadNotifications();
    }

    public function loadNotifications()
    {
        // Get latest 5 notifications using service
        $allNotifications = $this->notificationService->getAllNotifications(Auth::id());
        $this->notifications = $allNotifications->take(5);
        $this->latests = $allNotifications->count();
        $this->unreadNotificationsNumber = $this->notificationService->getUnreadCount(Auth::id());
    }

    public function markAsRead($id)
    {
        $result = $this->notificationService->markAsRead(Auth::id(), $id);
        if ($result) {
            $this->loadNotifications();
            $this->dispatch('notificationUpdated');
        }
    }

    public function markThemAllRead()
    {
        $result = $this->notificationService->markAllAsRead(Auth::id());
        if ($result) {
            $this->loadNotifications();
            $this->dispatch('notificationUpdated');
        }
    }

    public function render()
    {
        return view('livewire.notification-dropdown');
    }
}
