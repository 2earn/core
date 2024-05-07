<?php

namespace App\Http\Livewire;

use Core\Models\translatearabes;
use Core\Models\translateenglishs;
use Core\Models\translatetabs;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\File;
use Livewire\Component;
use  Livewire\WithPagination;

class TranslateView extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $arabicValue = "";
    public $frenchValue = "";
    public $englishValue = "";
    public $idTranslate;
    public $tab = [];
    public $tabfin = [];
    public $tabfinFr = [];
    public $tabfinEn = [];
    public $search = '';
    public $nbrPagibation = 10;
    protected $rules = [
        'frenchValue' => 'required'
    ];
    protected $listeners = [
        'AddFieldTranslate' => 'AddFieldTranslate',
        'addArabicField' => 'addArabicField',
        'addEnglishField' => 'addEnglishField',
        'mergeTransaction' => 'mergeTransaction',
        'databaseToFile' => 'databaseToFile'
    ];

    public function PreImport($param)
    {
        $this->dispatchBrowserEvent('PassEnter', [
            'type' => 'warning',
            'title' => "Opt",
            'text' => '',
            'ev' => $param
        ]);
    }

    public function AddFieldTranslate($val)
    {
        $flight = translatetabs::create([
            'name' => $val,
            'value' => '',
            'valueFr' => '',
            'valueEn' => ''
        ]);
        return redirect()->route('translate', app()->getLocale());
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function mergeTransaction($pass)
    {
        if ($pass != '159159') return;
        try {
            translatetabs::truncate();
            $pathFile = resource_path() . '/lang/en.json';
            $contents = File::get($pathFile);
            $json = collect(json_decode($contents));

            foreach ($json as $key => $value) {
                $flight = translatetabs::create([
                    'name' => $key,
                    'valueEn' => $value,
                    'value' => '',
                    'valueFr' => '',
                ]);
            }
            $pathFileAr = resource_path() . '/lang/ar.json';
            $contentsAr = File::get($pathFileAr);
            $jsonAr = collect(json_decode($contentsAr));
            foreach ($jsonAr as $key => $value) {
                translatetabs::where('name', $key)->update(['value' => $value]);
            }
            $pathFileFr = resource_path() . '/lang/fr.json';
            $contentsFr = File::get($pathFileFr);
            $jsonFr = collect(json_decode($contentsFr));
            foreach ($jsonFr as $key => $value) {
                translatetabs::where('name', $key)->update(['valueFr' => $value]);
            }
        } catch (FileNotFoundException $exception) {
            die("The file doesn't exist");
        }
    }

    public function addEnglishField($pass)
    {
        if ($pass != '159159') return;
        try {
            $pathFile = resource_path() . '\lang\en.json';
            $contents = File::get($pathFile);
            $json = collect(json_decode($contents));
            foreach ($json as $key => $value) {
                $flight = translateenglishs::create([
                    'name' => $key,
                    'value' => $value,
                ]);
            }
        } catch (FileNotFoundException $exception) {
            die("The file doesn't exist");
        }
    }

    public function addArabicField($pass)
    {
        if ($pass != '159159') return;
        try {
            $pathFile = resource_path() . '\lang\ar.json';
            $contents = File::get($pathFile);
            $json = collect(json_decode($contents));
            foreach ($json as $key => $value) {
                $flight = translatearabes::create([
                    'name' => $key,
                    'value' => $value,
                ]);
            }
        } catch (Illuminate\Contracts\Filesystem\FileNotFoundException $exception) {
            die("The file doesn't exist");
        }
    }

    public function databaseToFile($pass)
    {
        if ($pass != '159159') return;
        $all = translatetabs::all();
        foreach ($all as $key => $value) {
            $this->tabfin[$value->name] = $value->value;
            $this->tabfinFr[$value->name] = $value->valueFr;
            $this->tabfinEn[$value->name] = $value->valueEn;
        }
        try {
            $pathFile = resource_path() . '/lang/ar.json';
            $pathFileFr = resource_path() . '/lang/fr.json';
            $pathFileEn = resource_path() . '/lang/en.json';
            File::put($pathFile, json_encode($this->tabfin, JSON_UNESCAPED_UNICODE));
            File::put($pathFileFr, json_encode($this->tabfinFr, JSON_UNESCAPED_UNICODE));
            File::put($pathFileEn, json_encode($this->tabfinEn, JSON_UNESCAPED_UNICODE));
        } catch (Illuminate\Contracts\Filesystem\FileNotFoundException $exception) {
            die("The file doesn't exist");
        }
        $this->dispatchBrowserEvent('closeModal');
    }

    public function render()
    {
        $translate = translatetabs::where('name', 'like', '%' . $this->search . '%')
            ->orWhere('valueFr', 'like', '%' . $this->search . '%')
            ->orWhere('valueEn', 'like', '%' . $this->search . '%')
            ->orWhere('value', 'like', '%' . $this->search . '%')
            ->orderBy('id', 'desc')
            ->paginate($this->nbrPagibation);
        return view('livewire.translate-view', ["translates" => $translate])->extends('layouts.master')->section('content');
    }

    public function save()
    {
        foreach ($this->translate as $key => $value) {
            translatetabs::where('id', $value->id)->update(['value' => $value->value]);
            translatetabs::where('id', $value->id)->update(['valueFr' => $value->valueFr]);
            $this->tabfin[$value->name] = $value->value;
            $this->tabfinFr[$value->name] = $value->valueFr;
        }
        try {
            $pathFile = resource_path() . '/lang/ar.json';
            $pathFileFr = resource_path() . '/lang/fr.json';
            $pathFileEn = resource_path() . '/lang/en.json';
            File::put($pathFile, json_encode($this->tabfin, JSON_UNESCAPED_UNICODE));
            File::put($pathFileFr, json_encode($this->tabfinFr, JSON_UNESCAPED_UNICODE));
            File::put($pathFileEn, json_encode($this->tabfinEn, JSON_UNESCAPED_UNICODE));
        } catch (Illuminate\Contracts\Filesystem\FileNotFoundException $exception) {
            die("The file doesn't exist");
        }
    }

    public function PreAjout()
    {
        $this->dispatchBrowserEvent('PreAjoutTrans', [
            'type' => 'warning',
            'title' => "Opt",
            'text' => '',
        ]);
    }

    public function saveTranslate()
    {
        translatetabs::where('id', $this->idTranslate)->update([
            'value' => $this->arabicValue,
            'valueFr' => $this->frenchValue,
            'valueEn' => $this->englishValue,
        ]);
        $all = translatetabs::all();
        foreach ($all as $key => $value) {
            $this->tabfin[$value->name] = $value->value;
            $this->tabfinFr[$value->name] = $value->valueFr;
            $this->tabfinEn[$value->name] = $value->valueEn;
        }
        try {
            $pathFile = resource_path() . '/lang/ar.json';
            $pathFileFr = resource_path() . '/lang/fr.json';
            $pathFileEn = resource_path() . '/lang/en.json';
            File::put($pathFile, json_encode($this->tabfin, JSON_UNESCAPED_UNICODE));
            File::put($pathFileFr, json_encode($this->tabfinFr, JSON_UNESCAPED_UNICODE));
            File::put($pathFileEn, json_encode($this->tabfinEn, JSON_UNESCAPED_UNICODE));
        } catch (Illuminate\Contracts\Filesystem\FileNotFoundException $exception) {
            die("The file doesn't exist");
        }
        $this->dispatchBrowserEvent('closeModal');
    }

    public function initTranslate($idTranslate)
    {
        $trans = translatetabs::find($idTranslate);
        if ($trans) {
            $this->idTranslate = $trans->id;
            $this->arabicValue = $trans->value;
            $this->frenchValue = $trans->valueFr;
            $this->englishValue = $trans->valueEn;
        }
    }
}
