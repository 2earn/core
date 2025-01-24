<?php

namespace App\Http\Livewire;

use App\Models\News as ModelsNews;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class News extends Component
{

    public $listeners = ['delete' => 'delete'];

    public function delete($id)
    {
        try {
            ModelsNews::findOrFail($id)->delete();
            return redirect()->route('news_index', ['locale' => app()->getLocale()])->with('success', Lang::get('News Deleted Successfully'));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->route('news_index', ['locale' => app()->getLocale()])->with('danger', $exception->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.news')->extends('layouts.master')->section('content');
    }
}
