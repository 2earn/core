<?php

namespace App\Http\Livewire;

use Core\Models\Platform as ModelsPlatform;
use Illuminate\Support\Facades\Lang;
use Livewire\Component;

class Platform extends Component
{
    public $listeners = ['delete' => 'delete'];

    public function delete($id)
    {
        ModelsPlatform::findOrFail($id)->delete();
        return redirect()->route('platform_index', ['locale' => app()->getLocale()])->with('success', Lang::get('Platform Deleted Successfully!!'));
    }

    public function render()
    {
        return view('livewire.platform')->extends('layouts.master')->section('content');
    }
}
