<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\News;

class NewsShow extends Component
{
    public $id;
    public $news;

    public function mount($id)
    {
        $this->id = $id;
        $this->news = News::with('mainImage')->findOrFail($id);
    }

    public function render()
    {
        return view('livewire.news-show', [
            'news' => $this->news
        ])->extends('layouts.master')->section('content');
    }
}

