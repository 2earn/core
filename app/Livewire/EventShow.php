<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Event;

class EventShow extends Component
{
    public $id;
    public $event;

    public function mount($id)
    {
        $this->id = $id;
        $this->event = Event::with('mainImage')->findOrFail($id);
    }

    public function render()
    {
        return view('livewire.event-show', [
            'event' => $this->event
        ])->extends('layouts.master')->section('content');
    }
}

