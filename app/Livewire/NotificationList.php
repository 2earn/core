<?php

namespace App\Livewire;

use App\Services\NotificationService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class NotificationList extends Component
{
    const NOTIFICATION_PER_PAGE = 10;

    use WithPagination;

    protected NotificationService $notificationService;

    public $filter = 'all';

    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['notificationUpdated' => '$refresh'];

    public function boot(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function getNotificationsProperty()
    {
        return $this->notificationService->getPaginatedNotifications(
            Auth::id(),
            $this->filter,
            self::NOTIFICATION_PER_PAGE
        );
    }

    public function markAsRead($id)
    {
        $result = $this->notificationService->markAsRead(Auth::id(), $id);
        if ($result) {
            $this->dispatch('notificationUpdated');
        }
    }

    public function markThemAllRead()
    {
        $result = $this->notificationService->markAllAsRead(Auth::id());
        if ($result) {
            $this->dispatch('notificationUpdated');
        }
    }

    public function updatedFilter($value)
    {
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.notification-list', [
            'notifications' => $this->getNotificationsProperty(),
        ])->extends('layouts.master')->section('content');
    }
}
