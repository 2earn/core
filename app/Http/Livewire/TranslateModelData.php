<?php

namespace App\Http\Livewire;

use App\Models\TranslaleModel;
use Carbon\Carbon;
use Core\Models\translatearabes;
use Core\Models\translateenglishs;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Lang;
use Livewire\Component;
use Livewire\WithPagination;

class TranslateModelData extends Component
{
    use WithPagination;

    const TRANSLATION_PASSWORD = "159159";
    const TRANSLATION_PATH = '/lang/models/';
    protected $paginationTheme = 'bootstrap';
    public $arabicValue = "";
    public $frenchValue = "";
    public $englishValue = "";
    public $name;
    public $idTranslate;
    public $tab = [];
    public $tabfin = [];
    public $tabfinFr = [];
    public $tabfinEn = [];

    public $search = '';
    public $nbrPagibation = 10;

    protected $rules = ['frenchValue' => 'required'];
    protected $listeners = [
        'AddFieldTranslate' => 'AddFieldTranslate',
        'mergeTransaction' => 'mergeTransaction',
        'databaseToFile' => 'databaseToFile',
        'deleteTranslate' => 'deleteTranslate'
    ];

    public function PreImport($param)
    {
        $this->dispatchBrowserEvent('PassEnter', ['type' => 'warning', 'title' => "Opt", 'text' => '', 'ev' => $param]);
    }

    public function AddFieldTranslate($val)
    {
        if (TranslaleModel::where(DB::raw('upper(name)'), strtoupper($val))->get()->count() == 0) {
            TranslaleModel::create(['name' => $val, 'value' => $val . ' AR', 'valueFr' => $val . ' FR', 'valueEn' => $val . ' EN']);
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

    public function mergeTransaction($pass)
    {
        if ($pass != self::TRANSLATION_PASSWORD) return;
        try {
            TranslaleModel::truncate();
            $pathFile = resource_path() . self::TRANSLATION_PATH . 'en.json';
            $contents = File::get($pathFile);
            $json = collect(json_decode($contents));

            foreach ($json as $key => $value) {
                if ($value) {
                    if (TranslaleModel::where('name', $key)->get()->count() == 0) {
                        TranslaleModel::create(['name' => $key, 'valueEn' => $value, 'value' => '', 'valueFr' => '']);
                    } else {
                        TranslaleModel::where('name', $key)->update(['valueEn' => $value]);
                    }
                }
            }

            $pathFileAr = resource_path() . self::TRANSLATION_PATH . 'ar.json';
            $contentsAr = File::get($pathFileAr);
            $jsonAr = collect(json_decode($contentsAr));
            foreach ($jsonAr as $key => $value) {
                TranslaleModel::where('name', $key)->update(['value' => $value]);
            }

            $pathFileFr = resource_path() . self::TRANSLATION_PATH . 'fr.json';
            $contentsFr = File::get($pathFileFr);
            $jsonFr = collect(json_decode($contentsFr));
            foreach ($jsonFr as $key => $value) {
                TranslaleModel::where('name', $key)->update(['valueFr' => $value]);
            }
        } catch (\Exception $exception) {
            $this->dispatchBrowserEvent('closeModal');
            return redirect()->route('translate_model_data', app()->getLocale())->with('danger', trans('Translation merge failed') . ' : ' . Lang::get($exception->getMessage()));
        }
        $this->dispatchBrowserEvent('closeModal');
        return redirect()->route('translate_model_data', app()->getLocale())->with('success', trans('Translation merged successfully'));
    }



    public function databaseToFile($pass)
    {
        if ($pass != self::TRANSLATION_PASSWORD) return;
        $all = TranslaleModel::all();
        foreach ($all as $key => $value) {
            $this->tabfin[$value->name] = $value->value;
            $this->tabfinFr[$value->name] = $value->valueFr;
            $this->tabfinEn[$value->name] = $value->valueEn;
        }
        try {
            $pathFile = resource_path() . self::TRANSLATION_PATH . 'ar.json';
            $pathFileFr = resource_path() . self::TRANSLATION_PATH . 'fr.json';
            $pathFileEn = resource_path() . self::TRANSLATION_PATH . 'en.json';
            File::put($pathFile, json_encode($this->tabfin, JSON_UNESCAPED_UNICODE));
            File::put($pathFileFr, json_encode($this->tabfinFr, JSON_UNESCAPED_UNICODE));
            File::put($pathFileEn, json_encode($this->tabfinEn, JSON_UNESCAPED_UNICODE));
        } catch (\Exception $exception) {
            return redirect()->route('translate_model_data', app()->getLocale())->with('danger', trans('Keys to database  failed') . ' : ' . Lang::get($exception->getMessage()));
        }
        return redirect()->route('translate_model_data', app()->getLocale())->with('success', trans('Keys to database added successfully'));
    }

    public function render()
    {
        $translate = TranslaleModel::where(DB::raw('upper(name)'), 'like', '%' . strtoupper($this->search) . '%')
            ->orWhere(DB::raw('upper(valueFr)'), 'like', '%' . strtoupper($this->search) . '%')
            ->orWhere(DB::raw('upper(valueEn)'), 'like', '%' . strtoupper($this->search) . '%')
            ->orWhere(DB::raw('upper(value)'), 'like', '%' . strtoupper($this->search) . '%')
            ->orderBy('id', 'desc')
            ->paginate($this->nbrPagibation);
        return view('livewire.translate-model-data', ["translates" => $translate])->extends('layouts.master')->section('content');
    }

    public function save()
    {
        foreach ($this->translate as $key => $value) {
            TranslaleModel::where('id', $value->id)->update(['value' => $value->value]);
            TranslaleModel::where('id', $value->id)->update(['valueFr' => $value->valueFr]);
            $this->tabfin[$value->name] = $value->value;
            $this->tabfinFr[$value->name] = $value->valueFr;
        }
        try {
            $pathFile = resource_path() . self::TRANSLATION_PATH . 'ar.json';
            $pathFileFr = resource_path() . self::TRANSLATION_PATH . 'fr.json';
            $pathFileEn = resource_path() . self::TRANSLATION_PATH . 'en.json';
            File::put($pathFile, json_encode($this->tabfin, JSON_UNESCAPED_UNICODE));
            File::put($pathFileFr, json_encode($this->tabfinFr, JSON_UNESCAPED_UNICODE));
            File::put($pathFileEn, json_encode($this->tabfinEn, JSON_UNESCAPED_UNICODE));
        } catch (\Exception $exception) {
            return redirect()->route('translate_model_data', app()->getLocale())->with('danger', trans('Keys to files  failed') . ' : ' . Lang::get($exception->getMessage()));
        }
        return redirect()->route('translate_model_data', app()->getLocale())->with('success', trans('Keys to files added successfully'));
    }

    public function PreAjout()
    {
        $this->dispatchBrowserEvent('PreAjoutTrans', ['type' => 'warning', 'title' => "Opt", 'text' => '']);
    }

    public function saveTranslate()
    {
        TranslaleModel::where('id', $this->idTranslate)->update(['value' => $this->arabicValue, 'valueFr' => $this->frenchValue, 'valueEn' => $this->englishValue]);
        $all = TranslaleModel::all();
        foreach ($all as $key => $value) {
            $this->tabfin[$value->name] = $value->value;
            $this->tabfinFr[$value->name] = $value->valueFr;
            $this->tabfinEn[$value->name] = $value->valueEn;
        }
        try {
            $pathFile = resource_path() .  self::TRANSLATION_PATH.'ar.json';
            $pathFileFr = resource_path() . self::TRANSLATION_PATH. 'fr.json';
            $pathFileEn = resource_path() . self::TRANSLATION_PATH. 'en.json';
            File::put($pathFile, json_encode($this->tabfin, JSON_UNESCAPED_UNICODE));
            File::put($pathFileFr, json_encode($this->tabfinFr, JSON_UNESCAPED_UNICODE));
            File::put($pathFileEn, json_encode($this->tabfinEn, JSON_UNESCAPED_UNICODE));
        } catch (\Exception $exception) {
            return redirect()->route('translate_model_data', app()->getLocale())->with('danger', trans('Edit translation failed') . " " . Lang::get($exception->getMessage()));
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
        }
    }
}
