<?php

namespace App\Http\Livewire;

use App\Jobs\TranslationDatabaseToFiles;
use App\Jobs\TranslationFilesToDatabase;
use Carbon\Carbon;
use Core\Models\translatearabes;
use Core\Models\translateenglishs;
use Core\Models\translatetabs;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;

class TranslateView extends Component
{
    use WithPagination;


    const SEPARATION = ' : ';
    protected $paginationTheme = 'bootstrap';
    public $arabicValue = "";
    public $frenchValue = "";
    public $englishValue = "";
    public $spanishValue = "";
    public $turkishValue = "";

    public $name;
    public $idTranslate;
    public $tab = [];
    public $tabfin = [];
    public $tabfinFr = [];
    public $tabfinEn = [];
    public $tabfinTr = [];
    public $tabfinEs = [];

    public $search = '';
    public $nbrPagibation = 10;
    public $defRandomNumber;
    public $randomNumber;

    protected $rules = [
        'frenchValue' => 'required',
        'englishValue' => 'required',
        'spanishValue' => 'required',
        'turkishValue' => 'required',
        'arabicValue' => 'required',
    ];
    protected $listeners = [
        'AddFieldTranslate' => 'AddFieldTranslate',
        'mergeTransaction' => 'mergeTransaction',
        'databaseToFile' => 'databaseToFile',
        'deleteTranslate' => 'deleteTranslate'
    ];

    public function mount()
    {
        $this->defRandomNumber = mt_rand(999, 10000);
        $this->page = request()->query('page', 1);
    }


    public function PreImport($param)
    {
        $this->dispatchBrowserEvent('PassEnter', ['type' => 'warning', 'title' => "Opt", 'text' => '', 'ev' => $param]);
    }

    public function AddFieldTranslate($val)
    {
        if (!translatetabs::where(DB::raw('BINARY `name`'), $val)->exists()) {
            $translateTab = [
                'name' => $val,
                'value' => $val . ' AR',
                'valueFr' => $val . ' FR',
                'valueEn' => $val . ' EN',
                'valueTr' => $val . ' TR',
                'valueEs' => $val . ' ES'
            ];
            translatetabs::create($translateTab);
            return redirect()->route('translate', app()->getLocale())->with('success', trans('key added successfully') . self::SEPARATION . $val);
        } else {
            return redirect()->route('translate', app()->getLocale())->with('danger', trans('key exist!'));
        }
    }

    public function deleteTranslate($idTranslate)
    {
        translatetabs::where('id', $idTranslate)->delete();
        return redirect()->route('translate', app()->getLocale())->with('success', Lang::get('Translation item deleted'));
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function mergeTransaction($pass)
    {
        try {
            if (empty($pass) or $pass != $this->defRandomNumber) {
                throw new \Exception(trans('Key not confirmed'));
            }
            $start_time = microtime(true);
            $job = new TranslationFilesToDatabase();
            $job->handle();
            $end_time = microtime(true);
            $execution_time = formatSolde(($end_time - $start_time),3);
            Log::error(TranslationFilesToDatabase::class. self::SEPARATION . $execution_time);

        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            $this->dispatchBrowserEvent('closeModal');
            return redirect()->route('translate', app()->getLocale())->with('danger', trans('Translation merge operation failed') . self::SEPARATION . Lang::get($exception->getMessage()));
        }
        $this->dispatchBrowserEvent('closeModal');
        return redirect()->route('translate', app()->getLocale())->with('success', trans('Translation merge operation started successfully') . " " . self::SEPARATION . trans('Execution time') . " " . $execution_time . " " . trans('seconds'));
    }


    public function databaseToFile($pass)
    {
        try {
            if (empty($pass) or $pass != $this->defRandomNumber) {
                throw new \Exception(trans('Key not confirmed'));
            }
            $start_time = microtime(true);
            $job = new TranslationDatabaseToFiles();
            $job->handle();
            $end_time = microtime(true);
            $execution_time = formatSolde(($end_time - $start_time),3);
            Log::error(TranslationDatabaseToFiles::class. self::SEPARATION . $execution_time);

        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->route('translate', app()->getLocale())->with('danger', trans('Keys to database  failed') . self::SEPARATION . Lang::get($exception->getMessage()));
        }
        return redirect()->route('translate', app()->getLocale())->with('success', trans('Keys to database operation started successfully') . self::SEPARATION . trans('Execution time') . " " . $execution_time . " " . trans('seconds'));
    }

    public function PreAjout()
    {
        $this->dispatchBrowserEvent('PreAjoutTrans', ['type' => 'warning', 'title' => "Opt", 'text' => '']);
    }

    public function getCurrentPage()
    {
        return $this->pageNumber();
    }
    public function saveTranslate()
    {
        $params = [
            'value' => $this->arabicValue,
            'valueFr' => $this->frenchValue,
            'valueEn' => $this->englishValue,
            'valueEs' => $this->spanishValue,
            'valueTr' => $this->turkishValue,
        ];
        translatetabs::where('id', $this->idTranslate)->update($params);
        $all = translatetabs::all();
        foreach ($all as $key => $value) {
            $this->tabfin[$value->name] = $value->value;
            $this->tabfinFr[$value->name] = $value->valueFr;
            $this->tabfinEn[$value->name] = $value->valueEn;
            $this->tabfinEs[$value->name] = $value->valueEs;
            $this->tabfinTr[$value->name] = $value->valueTr;
        }
        try {
            $pathFile = resource_path() . '/lang/ar.json';
            $pathFileFr = resource_path() . '/lang/fr.json';
            $pathFileEn = resource_path() . '/lang/en.json';
            $pathFileTr = resource_path() . '/lang/tr.json';
            $pathFileEs = resource_path() . '/lang/es.json';
            File::put($pathFile, json_encode($this->tabfin, JSON_UNESCAPED_UNICODE));
            File::put($pathFileFr, json_encode($this->tabfinFr, JSON_UNESCAPED_UNICODE));
            File::put($pathFileEn, json_encode($this->tabfinEn, JSON_UNESCAPED_UNICODE));
            File::put($pathFileTr, json_encode($this->tabfinTr, JSON_UNESCAPED_UNICODE));
            File::put($pathFileEs, json_encode($this->tabfinEs, JSON_UNESCAPED_UNICODE));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->route('translate', ['locale' => app()->getLocale(), 'page' => $this->page])->with('danger', trans('Edit translation failed') . " " . Lang::get($exception->getMessage()));
        }
        return redirect()->route('translate', ['locale' => app()->getLocale(), 'page' => $this->page])->with('success', trans('Edit translation succeeded'));
    }

    public function initTranslate($idTranslate)
    {
        $trans = translatetabs::find($idTranslate);
        if ($trans) {
            $this->idTranslate = $trans->id;
            $this->name = $trans->name;
            $this->arabicValue = $trans->value;
            $this->frenchValue = $trans->valueFr;
            $this->englishValue = $trans->valueEn;
            $this->turkishValue = $trans->valueTr;
            $this->spanishValue = $trans->valueEs;
        }
    }

    public function render()
    {
        $translate = translatetabs::where(DB::raw('upper(name)'), 'like', '%' . strtoupper($this->search) . '%')
            ->orWhere(DB::raw('BINARY `name`'), 'like', '%' . strtoupper($this->search) . '%')
            ->orWhere(DB::raw('upper(valueFr)'), 'like', '%' . strtoupper($this->search) . '%')
            ->orWhere(DB::raw('upper(valueEn)'), 'like', '%' . strtoupper($this->search) . '%')
            ->orWhere(DB::raw('upper(valueEs)'), 'like', '%' . strtoupper($this->search) . '%')
            ->orWhere(DB::raw('upper(valueTr)'), 'like', '%' . strtoupper($this->search) . '%')
            ->orWhere(DB::raw('upper(value)'), 'like', '%' . strtoupper($this->search) . '%')
            ->orderBy('id', 'desc')
            ->paginate($this->nbrPagibation);
        return view('livewire.translate-view', ["translates" => $translate])->extends('layouts.master')->section('content');
    }
}
