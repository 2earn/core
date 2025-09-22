<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Event;
use App\Models\Comment;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

class EventItem extends Component
{
    public $idEvent;
    public $like;
    public $comment;
    public $currentRouteName;

    public function mount($idEvent)
    {
        $this->idEvent = $idEvent;
        $this->currentRouteName = Route::currentRouteName();
        $this->like = Event::whereHas('likes', function ($q) {
            $q->where('user_id', auth()->user()->id)->where('likable_id', $this->idEvent);
        })->exists();
    }

    public function like()
    {
        $event = Event::findOrFail($this->idEvent);
        $event->likes()->create(['user_id' => auth()->user()->id]);
        $this->like = true;
    }

    public function dislike()
    {
        $event = Event::findOrFail($this->idEvent);
        $event->likes()->where('user_id', auth()->user()->id)->delete();
        $this->like = false;
    }

    public function addComment()
    {
        if (empty($this->comment)) {
            return redirect()->route('event_item', ['locale' => app()->getLocale(), 'idEvent' => $this->idEvent])->with('danger', Lang::get('Empty comment'));
        }
        $event = Event::findOrFail($this->idEvent);
        $event->comments()->create(['user_id' => auth()->user()->id, 'content' => $this->comment]);
        $this->comment = "";
    }

    public function deleteComment($idComment)
    {
        Comment::findOrFail($idComment)->delete();
    }

    public function render()
    {
        $event = Event::with(['mainImage', 'likes', 'comments.user'])->find($this->idEvent);
        return view('livewire.event-item', compact('event'))->extends('layouts.master')->section('content');
    }
}

