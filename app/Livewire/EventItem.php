<?php

namespace App\Livewire;

use App\Services\CommentService;
use App\Services\EventService;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Route;
use Livewire\Component;

class EventItem extends Component
{
    protected EventService $eventService;
    protected CommentService $commentService;

    public $idEvent;
    public $like;
    public $comment;
    public $currentRouteName;

    public function boot(EventService $eventService, CommentService $commentService)
    {
        $this->eventService = $eventService;
        $this->commentService = $commentService;
    }

    public function mount($idEvent)
    {
        $this->idEvent = $idEvent;
        $this->currentRouteName = Route::currentRouteName();
        $this->like = $this->eventService->hasUserLiked($this->idEvent, auth()->user()->id);
    }

    public function like()
    {
        $success = $this->eventService->addLike($this->idEvent, auth()->user()->id);
        if ($success) {
            $this->like = true;
        }
    }

    public function dislike()
    {
        $success = $this->eventService->removeLike($this->idEvent, auth()->user()->id);
        if ($success) {
            $this->like = false;
        }
    }

    public function addComment()
    {
        if (empty($this->comment)) {
            return redirect()->route('event_item', ['locale' => app()->getLocale(), 'idEvent' => $this->idEvent])
                ->with('danger', Lang::get('Empty comment'));
        }

        $success = $this->eventService->addComment($this->idEvent, auth()->user()->id, $this->comment);
        if ($success) {
            $this->comment = "";
        }
    }

    public function deleteComment($idComment)
    {
        $this->commentService->delete($idComment);
    }

    public function render()
    {
        $event = $this->eventService->getWithRelationships($this->idEvent);
        return view('livewire.event-item', compact('event'))
            ->extends('layouts.master')
            ->section('content');
    }
}

