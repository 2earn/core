<?php

namespace App\Livewire;

use App\Models\TranslaleModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Livewire\Component;

class TranslationHtmlEditor extends Component
{
    public $translateModel = '';
    public $content = '';
    public $lang = '';
    public $idT = '';

    public function mount(Request $request)
    {
        $this->idT = Route::current()->parameter('id');
        $this->lang = Route::current()->parameter('lang');
        $this->flag = $this->lang;
        if ($this->flag == 'ar') {
            $this->flag = 'sa';
        }
        if ($this->flag == 'en') {
            $this->flag = 'gb';
        }

        $this->translateModel = TranslaleModel::find($this->idT);

        $this->content = match ($this->lang) {
            'ar' => $this->translateModel->value,
            'fr' => $this->translateModel->valueFr,
            'en' => $this->translateModel->valueEn,
            'es' => $this->translateModel->valueEs,
            'tr' => $this->translateModel->valueTr,
            'ru' => $this->translateModel->valueRu,
            'de' => $this->translateModel->valueDe,
        };
    }

    public function render()
    {
        return view('livewire.translation-html-editor')->extends('layouts.master')->section('content');
    }
}
