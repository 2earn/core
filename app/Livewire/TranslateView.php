<?php

namespace App\Livewire;

use App\Jobs\TranslationDatabaseToFiles;
use App\Jobs\TranslationFilesToDatabase;
use App\Services\Translation\TranslateTabsService;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;

class TranslateView extends Component
{
    use WithPagination;

    const SEPARATION = ' : ';
    protected $paginationTheme = 'bootstrap';
    protected TranslateTabsService $translateService;

    public $arabicValue = "";
    public $frenchValue = "";
    public $englishValue = "";
    public $spanishValue = "";
    public $turkishValue = "";
    public $russianValue = "";
    public $germanValue = "";

    public $page;
    public $name;
    public $idTranslate;
    public $tab = [];

    public $search = '';
    public $nbrPagibation = 10;
    public $nbrPagibationArray = [5, 10, 25, 50, 100];
    public $defRandomNumber;
    public $randomNumber;

    protected $rules = [
        'frenchValue' => 'required',
        'englishValue' => 'required',
        'spanishValue' => 'required',
        'turkishValue' => 'required',
        'arabicValue' => 'required',
        'russianValue' => 'required',
        'germanValue' => 'required',
    ];
    protected $listeners = [
        'AddFieldTranslate' => 'AddFieldTranslate',
        'mergeTransaction' => 'mergeTransaction',
        'databaseToFile' => 'databaseToFile',
        'deleteTranslate' => 'deleteTranslate'
    ];

    public function boot(TranslateTabsService $translateService)
    {
        $this->translateService = $translateService;
    }

    public function mount()
    {
        $this->defRandomNumber = mt_rand(999, 10000);
        $this->page = request()->query('page', 1);
    }


    public function PreImport($param)
    {
        $this->dispatch('PassEnter', ['type' => 'warning', 'title' => "Opt", 'text' => '', 'ev' => $param]);
    }

    public function AddFieldTranslate($val)
    {
        if (!$this->translateService->exists($val)) {
            $this->translateService->create($val);
            return redirect()->route('translate', app()->getLocale())->with('success', trans('key added successfully') . self::SEPARATION . $val);
        } else {
            return redirect()->route('translate', app()->getLocale())->with('danger', trans('key exist!'));
        }
    }

    public function deleteTranslate($idTranslate)
    {
        $this->translateService->delete($idTranslate);
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
            $execution_time = formatSolde(($end_time - $start_time), 3);
            Log::error(TranslationFilesToDatabase::class . self::SEPARATION . $execution_time);

        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            $this->dispatch('closeModal');
            return redirect()->route('translate', app()->getLocale())->with('danger', trans('Translation merge operation failed') . self::SEPARATION . Lang::get($exception->getMessage()));
        }
        $this->dispatch('closeModal');
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
            $execution_time = formatSolde(($end_time - $start_time), 3);
            Log::error(TranslationDatabaseToFiles::class . self::SEPARATION . $execution_time);

        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->route('translate', app()->getLocale())->with('danger', trans('Keys to database  failed') . self::SEPARATION . Lang::get($exception->getMessage()));
        }
        return redirect()->route('translate', app()->getLocale())->with('success', trans('Keys to database operation started successfully') . self::SEPARATION . trans('Execution time') . " " . $execution_time . " " . trans('seconds'));
    }

    public function PreAjout()
    {
        $this->dispatch('PreAjoutTrans', ['type' => 'warning', 'title' => "Opt", 'text' => '']);
    }

    public function getCurrentPage()
    {
        return $this->pageNumber();
    }

    public function saveTranslate()
    {
        try {
            $params = [
                'value' => $this->arabicValue,
                'valueFr' => $this->frenchValue,
                'valueEn' => $this->englishValue,
                'valueEs' => $this->spanishValue,
                'valueTr' => $this->turkishValue,
                'valueRu' => $this->russianValue,
                'valueDe' => $this->germanValue,
            ];
            $this->translateService->update($this->idTranslate, $params);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->route('translate', ['locale' => app()->getLocale(), 'page' => $this->page])->with('danger', trans('Edit translation failed') . " " . Lang::get($exception->getMessage()));
        }
        return redirect()->route('translate', ['locale' => app()->getLocale(), 'page' => $this->page])->with('success', trans('Edit translation succeeded'));
    }

    public function initTranslate($idTranslate)
    {
        $trans = $this->translateService->getById($idTranslate);
        if ($trans) {
            $this->idTranslate = $trans->id;
            $this->name = $trans->name;
            $this->arabicValue = $trans->value;
            $this->frenchValue = $trans->valueFr;
            $this->englishValue = $trans->valueEn;
            $this->turkishValue = $trans->valueTr;
            $this->spanishValue = $trans->valueEs;
            $this->russianValue = $trans->valueRu;
            $this->germanValue = $trans->valueDe;
        }
    }

    public function render()
    {
        $translate = $this->translateService->getPaginated($this->search, $this->nbrPagibation);
        return view('livewire.translate-view', ["translates" => $translate])->extends('layouts.master')->section('content');
    }
}
