<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class NotificationList extends Component
{
    use WithPagination;

    public $filter = 'all';

    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['refreshNotifications' => '$refresh'];

    public function getNotificationsProperty()
    {
        $query = Auth::user()->notifications()->latest();

        if ($this->filter === 'unread') {
            $query->whereNull('read_at');
        } elseif ($this->filter === 'read') {
            $query->whereNotNull('read_at');
        }

        return $query->paginate(10);
    }

    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->find($id);
        if ($notification) {
            $notification->markAsRead();
        }
    }

    public function updatedFilter($value)
    {
        logger()->info("Filter changed to: {$value}");
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.notification-list', [
            'notifications' => $this->getNotificationsProperty(),
        ])->extends('layouts.master')->section('content');
    }
}
