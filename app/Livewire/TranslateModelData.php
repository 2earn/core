<?php

namespace App\Livewire;

use App\Models\TranslaleModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Livewire\Component;
use Livewire\WithPagination;

class TranslateModelData extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

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

    public function mount(Request $request)
    {
        $search = $request->input('search');
        if (!empty($search)) {
            $this->search = $search;
        }
    }


    public function AddFieldTranslate($val)
    {
        if (TranslaleModel::where(DB::raw('upper(name)'), strtoupper($val))->get()->count() == 0) {
            $translateTab = [
                'name' => $val,
                'value' => $val . ' AR',
                'valueFr' => $val . ' FR',
                'valueEn' => $val . ' EN',
                'valueTr' => $val . ' TR',
                'valueEs' => $val . ' ES',
                'valueRu' => $val . ' RU',
                'valueDe' => $val . ' DE'
            ];
            TranslaleModel::create($translateTab);
            return redirect()->route('translate_model_data', app()->getLocale())->with('success', trans('key added successfully') . ' : ' . $val);
        } else {
            return redirect()->route('translate_model_data', app()->getLocale())->with('danger', trans('key exist!'));
        }
    }

    public function deleteTranslate($idTranslate)
    {
        TranslaleModel::where('id', $idTranslate)->delete();
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
        $params = ['value' => $this->arabicValue, 'valueFr' => $this->frenchValue, 'valueEn' => $this->englishValue, 'valueTr' => $this->turkishValue, 'valueEs' => $this->spanishValue, 'valueRu' => $this->russianValue, 'valueDe' => $this->germanValue];
        TranslaleModel::where('id', $this->idTranslate)->update($params);
        $all = TranslaleModel::all();
        foreach ($all as $value) {
            $this->tabfin[$value->name] = $value->value;
            $this->tabfinFr[$value->name] = $value->valueFr;
            $this->tabfinEn[$value->name] = $value->valueEn;
            $this->tabfinTr[$value->name] = $value->valueTr;
            $this->tabfinEs[$value->name] = $value->valueEs;
            $this->tabfinRu[$value->name] = $value->valueRu;
            $this->tabfinDe[$value->name] = $value->valueDe;
        }
        return redirect()->route('translate_model_data', app()->getLocale())->with('success', trans('Edit translation succeeded'));
    }

    public function initTranslate($idTranslate)
    {
        $trans = TranslaleModel::find($idTranslate);
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
        $translate = TranslaleModel::where(DB::raw('upper(name)'), 'like', '%' . strtoupper($this->search) . '%')
            ->orWhere(DB::raw('upper(valueFr)'), 'like', '%' . strtoupper($this->search) . '%')
            ->orWhere(DB::raw('upper(valueFr)'), 'like', '%' . strtoupper($this->search) . '%')
            ->orWhere(DB::raw('upper(valueEn)'), 'like', '%' . strtoupper($this->search) . '%')
            ->orWhere(DB::raw('upper(valueEs)'), 'like', '%' . strtoupper($this->search) . '%')
            ->orWhere(DB::raw('upper(valueTr)'), 'like', '%' . strtoupper($this->search) . '%')
            ->orWhere(DB::raw('upper(valueRu)'), 'like', '%' . strtoupper($this->search) . '%')
            ->orWhere(DB::raw('upper(valueDe)'), 'like', '%' . strtoupper($this->search) . '%')
            ->orWhere(DB::raw('upper(value)'), 'like', '%' . strtoupper($this->search) . '%')
            ->orderBy('id', 'desc')
            ->paginate($this->nbrPagibation);

        return view('livewire.translate-model-data', ["translates" => $translate])->extends('layouts.master')->section('content');
    }

}
