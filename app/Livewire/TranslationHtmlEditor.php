<?php

namespace App\Livewire;

use App\Models\TranslaleModel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Livewire\Component;

class TranslationHtmlEditor extends Component
{
    public $translateModel = '';
    public $content = '';
    public $lang = '';
    public $flag = '';
    public $idT = '';

    protected $listeners = [
        'saveTranslation' => 'saveTranslation'
    ];

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

    public function saveTranslation()
    {
        $redirectParams=['locale' => app()->getLocale(), 'id' => $this->idT, 'lang' => $this->lang];
        try {
            $this->translateModel = TranslaleModel::find($this->idT);
            match ($this->lang) {
                'ar' => $this->translateModel->value = $this->content,
                'fr' => $this->translateModel->valueFr = $this->content,
                'en' => $this->translateModel->valueEn = $this->content,
                'es' => $this->translateModel->valueEs = $this->content,
                'tr' => $this->translateModel->valueTr = $this->content,
                'ru' => $this->translateModel->valueRu = $this->content,
                'de' => $this->translateModel->valueDe = $this->content,
            };
            $this->translateModel->save();
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->route('translate_html',  $redirectParams)->with('warning', Lang::get('Translation update Failed').' ('.$this->lang.')');
        }
        return redirect()->route('translate_html',  $redirectParams)->with('success', Lang::get('Translation updated Successfully').' ('.$this->lang.')');
    }

    public function render()
    {
        return view('livewire.translation-html-editor')->extends('layouts.master')->section('content');
    }
}
