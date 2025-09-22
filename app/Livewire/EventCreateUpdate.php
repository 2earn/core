<?php

namespace App\Livewire;

use App\Models\Event;
use App\Models\TranslaleModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithFileUploads;

class EventCreateUpdate extends Component
{
    use WithFileUploads;

    public $idEvent;
    public $update;
    public $enabled;
    public $title = '';
    public $content = '';
    public $published_at = '';
    public $start_at = '';
    public $end_at = '';
    public $mainImage;

    protected $rules = [
        'title' => 'required',
        'content' => 'required',
        'mainImage' => 'nullable|image|mimes:jpeg,png,jpg',
        'start_at' => 'nullable|date',
        'end_at' => 'nullable|date',
    ];

    public function mount(Request $request)
    {
        $this->idEvent = $request->input('id');
        if (!is_null($this->idEvent)) {
            $this->update = true;
            $this->edit($this->idEvent);
        } else {
            $this->update = false;
            $this->enabled = false;
        }
    }

    public function cancel()
    {
        return redirect()->route('event_index', ['locale' => app()->getLocale(), 'idEvent' => $this->idEvent])->with('warning', Lang::get('Event operation cancelled'));
    }

    public function edit($idEvent)
    {
        $event = Event::findOrFail($idEvent);
        $this->idEvent = $idEvent;
        $this->title = $event->title;
        $this->content = $event->content;
        $this->enabled = $event->enabled;
        $this->published_at = $event->published_at ? $event->published_at->format('Y-m-d\TH:i') : null;
        $this->start_at = $event->start_at ? $event->start_at->format('Y-m-d\TH:i') : null;
        $this->end_at = $event->end_at ? $event->end_at->format('Y-m-d\TH:i') : null;
    }

    public function save()
    {
        $this->validate();
        $data = [
            'title' => $this->title,
            'content' => $this->content,
            'enabled' => $this->enabled,
            'published_at' => $this->published_at,
            'start_at' => $this->start_at,
            'end_at' => $this->end_at,
        ];
        try {
            if ($this->idEvent) {
                Event::where('id', $this->idEvent)->update($data);
            } else {
                $event = Event::create($data);
                $this->idEvent = $event->id;
                $translations = ['title', 'content'];
                foreach ($translations as $translation) {
                    TranslaleModel::create([
                        'name' => TranslaleModel::getTranslateName($event, $translation),
                        'value' => $this->{$translation} . ' AR',
                        'valueFr' => $this->{$translation} . ' FR',
                        'valueEn' => $this->{$translation} . ' EN',
                        'valueTr' => $this->{$translation} . ' TR',
                        'valueEs' => $this->{$translation} . ' ES',
                        'valueRu' => $this->{$translation} . ' Ru',
                        'valueDe' => $this->{$translation} . ' De',
                    ]);
                }
            }
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->route('event_index', ['locale' => app()->getLocale()])->with('error', Lang::get('Event save failed'));
        }
        return redirect()->route('event_index', ['locale' => app()->getLocale()])->with('success', Lang::get('Event saved successfully'));
    }

    public function render()
    {
        return view('livewire.event-create-update')->extends('layouts.master')->section('content');
    }
}
