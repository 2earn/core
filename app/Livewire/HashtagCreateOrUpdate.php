<?php

namespace App\Livewire;

use App\Models\Hashtag;
use Illuminate\Support\Str;
use Livewire\Component;

class HashtagCreateOrUpdate extends Component
{
    public $hashtagId;
    public $name = '';
    public $slug = '';

    protected $rules = [
        'name' => 'required|string|max:255|unique:hashtags,name',
        'slug' => 'required|string|max:255|unique:hashtags,slug',
    ];

    public function mount($id = null)
    {
        if ($id) {
            $hashtag = Hashtag::findOrFail($id);
            $this->hashtagId = $hashtag->id;
            $this->name = $hashtag->name;
            $this->slug = $hashtag->slug;
            $this->rules['name'] .= ',' . $hashtag->id;
            $this->rules['slug'] .= ',' . $hashtag->id;
        }
    }

    public function updatedName()
    {
        $this->slug = Str::slug($this->name);
    }

    public function save()
    {
        $this->validate();
        if ($this->hashtagId) {
            $hashtag = Hashtag::findOrFail($this->hashtagId);
            $hashtag->update([
                'name' => $this->name,
                'slug' => $this->slug,
            ]);
            session()->flash('success', 'Hashtag updated successfully.');
        } else {
            Hashtag::create([
                'name' => $this->name,
                'slug' => $this->slug,
            ]);
            session()->flash('success', 'Hashtag created successfully.');
        }
        return redirect()->route('hashtags_index',app()->getLocale());
    }

    public function render()
    {
        return view('livewire.hashtag-create-or-update')->extends('layouts.master')->section('content');
    }
}
