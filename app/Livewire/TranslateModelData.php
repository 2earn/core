<?php

namespace App\Livewire;

use App\Models\TranslaleModel;
use App\Services\Translation\TranslaleModelService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Livewire\Component;
use Livewire\WithPagination;

class TranslateModelData extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    protected TranslaleModelService $translationService;

    public $arabicValue = "";
    public $frenchValue = "";
    public $englishValue = "";
    public $spanishValue = "";
    public $turkishValue = "";
    public $russianValue = "";
    public $germanValue = "";

    public $name;
    public $idTranslate;
    public $tab = [];
    public $tabfin = [];
    public $tabfinFr = [];
    public $tabfinEn = [];
    public $tabfinTr = [];
    public $tabfinEs = [];
    public $tabfinRu = [];
    public $tabfinDe = [];

    public $search = '';
    public $nbrPagibation = 10;
    public $nbrPagibationArray = [5, 10, 25, 50, 100];

    protected $rules = ['frenchValue' => 'required'];
    protected $listeners = [
        'AddFieldTranslate' => 'AddFieldTranslate',
        'mergeTransaction' => 'mergeTransaction',
        'deleteTranslate' => 'deleteTranslate'
    ];

    public function boot(TranslaleModelService $translationService)
    {
        $this->translationService = $translationService;
    }

    public function mount(Request $request)
    {
        $search = $request->input('search');
        if (!empty($search)) {
            $this->search = $search;
        }
    }


    public function AddFieldTranslate($val)
    {
        if (!$this->translationService->exists($val)) {
            $this->translationService->create($val);
            return redirect()->route('translate_model_data', app()->getLocale())->with('success', trans('key added successfully') . ' : ' . $val);
        } else {
            return redirect()->route('translate_model_data', app()->getLocale())->with('danger', trans('key exist!'));
        }
    }

    public function deleteTranslate($idTranslate)
    {
        $this->translationService->delete($idTranslate);
        return redirect()->route('translate_model_data', app()->getLocale())->with('success', Lang::get('Translation item deleted'));
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function PreAjout()
    {
        $this->dispatch('PreAjoutTrans', ['type' => 'warning', 'title' => "Opt", 'text' => '']);
    }

    public function saveTranslate()
    {
        $params = [
            'value' => $this->arabicValue,
            'valueFr' => $this->frenchValue,
            'valueEn' => $this->englishValue,
            'valueTr' => $this->turkishValue,
            'valueEs' => $this->spanishValue,
            'valueRu' => $this->russianValue,
            'valueDe' => $this->germanValue
        ];

        $this->translationService->update($this->idTranslate, $params);

        // Get all translations as key-value arrays
        $allTranslations = $this->translationService->getAllAsKeyValueArrays();

        $this->tabfin = $allTranslations['ar'];
        $this->tabfinFr = $allTranslations['fr'];
        $this->tabfinEn = $allTranslations['en'];
        $this->tabfinTr = $allTranslations['tr'];
        $this->tabfinEs = $allTranslations['es'];
        $this->tabfinRu = $allTranslations['ru'];
        $this->tabfinDe = $allTranslations['de'];

        return redirect()->route('translate_model_data', app()->getLocale())->with('success', trans('Edit translation succeeded'));
    }

    public function initTranslate($idTranslate)
    {
        $trans = $this->translationService->getById($idTranslate);
        if ($trans) {
            $this->idTranslate = $trans->id;
            $this->name = $trans->name;
            $this->arabicValue = $trans->value;
            $this->frenchValue = $trans->valueFr;
            $this->englishValue = $trans->valueEn;
            $this->spanishValue = $trans->valueEs;
            $this->turkishValue = $trans->valueTr;
            $this->russianValue = $trans->valueRu;
            $this->germanValue = $trans->valueDe;
        }
    }

    public function render()
    {
        $translate = $this->translationService->getPaginated($this->search, $this->nbrPagibation);

        return view('livewire.translate-model-data', ["translates" => $translate])->extends('layouts.master')->section('content');
    }

}
