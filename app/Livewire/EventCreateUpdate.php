<?php

namespace App\Livewire;

use App\Models\Event;
use App\Services\EventService;
use App\Services\Hashtag\HashtagService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Livewire\Component;
use Livewire\WithFileUploads;

class EventCreateUpdate extends Component
{
    use WithFileUploads;

    protected EventService $eventService;
    protected HashtagService $hashtagService;

    public $idEvent;
    public $update;
    public $enabled;
    public $title = '';
    public $content = '';
    public $published_at = '';
    public $start_at = '';
    public $end_at = '';
    public $mainImage;
    public $location = '';

    public $allHashtags = [];
    public $selectedHashtags = [];

    protected $rules = [
        'title' => 'required',
        'content' => 'required',
        'mainImage' => 'nullable|image|mimes:jpeg,png,jpg',
        'start_at' => 'nullable|date',
        'end_at' => 'nullable|date',
    ];

    public function boot(EventService $eventService, HashtagService $hashtagService)
    {
        $this->eventService = $eventService;
        $this->hashtagService = $hashtagService;
    }

    public function mount(Request $request)
    {
        $this->allHashtags = $this->hashtagService->getAll();
        $this->selectedHashtags = [];
        $this->idEvent = $request->input('id');
        if (!is_null($this->idEvent)) {
            $this->update = true;
            $this->edit($this->idEvent);
        } else {
            $this->published_at = date('Y-m-d\TH:i');
            $this->start_at = date('Y-m-d\TH:i');
            $this->end_at = date('Y-m-d\TH:i');
            $this->update = false;
            $this->enabled = false;
        }
    }

    public function cancel()
    {
        return redirect()->route('event_index', ['locale' => app()->getLocale(), 'idEvent' => $this->idEvent])->with('warning', Lang::get('Event operation canceled'));
    }

    public function edit($idEvent)
    {
        $event = $this->eventService->findByIdOrFail($idEvent);
        $this->idEvent = $idEvent;
        $this->title = $event->title;
        $this->content = $event->content;
        $this->enabled = $event->enabled == 1 ? true : false;
        $this->published_at = $event->published_at ? $event->published_at->format('Y-m-d\TH:i') : null;
        $this->start_at = $event->start_at ? $event->start_at->format('Y-m-d\TH:i') : null;
        $this->end_at = $event->end_at ? $event->end_at->format('Y-m-d\TH:i') : null;
        $this->location = $event->location;
        $this->selectedHashtags = $event->hashtags()->get()->pluck('id')->toArray();

    }

    public function save()
    {
        $this->validate();
        $data = [
            'title' => $this->title,
            'content' => $this->content,
            'enabled' => $this->enabled,
            'published_at' => $this->published_at ? \Carbon\Carbon::parse($this->published_at)->format('Y-m-d H:i:s') : null,
            'start_at' => $this->start_at ? \Carbon\Carbon::parse($this->start_at)->format('Y-m-d H:i:s') : null,
            'end_at' => $this->end_at ? \Carbon\Carbon::parse($this->end_at)->format('Y-m-d H:i:s') : null,
            'location' => $this->location,
        ];

        try {
            if ($this->idEvent) {
                // Update existing event
                $this->eventService->update($this->idEvent, $data);
                $event = $this->eventService->getById($this->idEvent);

                if (!$event) {
                    return redirect()->route('event_index', ['locale' => app()->getLocale()])
                        ->with('error', Lang::get('Event not found'));
                }

                $event->hashtags()->sync($this->selectedHashtags);

                if ($this->mainImage) {
                    if (!is_null($event->mainImage)) {
                        \Illuminate\Support\Facades\Storage::disk('public2')->delete($event->mainImage->url);
                    }
                    $imagePath = $this->mainImage->store('events/' . Event::IMAGE_TYPE_MAIN, 'public2');
                    $event->mainImage()->delete();
                    $event->mainImage()->create([
                        'url' => $imagePath,
                        'type' => Event::IMAGE_TYPE_MAIN,
                    ]);
                }
            } else {
                // Create new event
                $event = $this->eventService->create($data);

                if (!$event) {
                    return redirect()->route('event_index', ['locale' => app()->getLocale()])
                        ->with('error', Lang::get('Event creation failed'));
                }

                $this->idEvent = $event->id;
                $event->hashtags()->sync($this->selectedHashtags);

                createTranslaleModel($event, 'title', $this->title);
                createTranslaleModel($event, 'content', $this->content);
                createTranslaleModel($event, 'location', $this->location);

                if ($this->mainImage) {
                    $imagePath = $this->mainImage->store('events/' . Event::IMAGE_TYPE_MAIN, 'public2');
                    $event->mainImage()->create([
                        'url' => $imagePath,
                        'type' => Event::IMAGE_TYPE_MAIN,
                    ]);
                }
            }
        } catch (\Exception $exception) {
            return redirect()->route('event_index', ['locale' => app()->getLocale()])
                ->with('error', Lang::get('Event save failed'));
        }

        return redirect()->route('event_index', ['locale' => app()->getLocale()])
            ->with('success', Lang::get('Event saved successfully'));
    }

    public function render()
    {
        $event = null;
        if ($this->idEvent) {
            $event = $this->eventService->getWithMainImage($this->idEvent);
        }
        return view('livewire.event-create-update', compact('event'))
            ->extends('layouts.master')
            ->section('content');
    }
}
