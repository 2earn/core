<?php

namespace App\Livewire;

use Livewire\Component;

class TranslationHtmlEditor extends Component
{
    public $content = '';

    public function render()
    {
        return view('livewire.translation-html-editor')->extends('layouts.master')->section('content');
    }
}
