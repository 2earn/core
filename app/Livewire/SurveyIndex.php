<?php

namespace App\Livewire;

use App\Models\User;
use App\Services\SurveyService;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Livewire\Component;
use Livewire\WithPagination;

class SurveyIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $disableNote = '';

    public $currentRouteName;
    protected $paginationTheme = 'bootstrap';

    protected SurveyService $surveyService;

    public function boot(SurveyService $surveyService)
    {
        $this->surveyService = $surveyService;
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function mount()
    {
        $this->currentRouteName = Route::currentRouteName();
    }

    public function enable($id)
    {
        try {
            $this->surveyService->enable($id);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->route('surveys_index', app()->getLocale())->with('danger', Lang::get('Something goes wrong while Enabling Survey'));
        }
        return redirect()->route('surveys_index', app()->getLocale())->with('success', Lang::get('Survey Enabled Successfully'));
    }

    public function disable($id)
    {
        try {
            if (empty($this->disableNote)) {
                throw new \Exception(Lang::get('Something goes wrong while Disabling Survey'));
            }
            $this->surveyService->disable($id, $this->disableNote);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->route('surveys_index', app()->getLocale())->with('danger', Lang::get('Something goes wrong while Disabling Survey'));
        }

        return redirect()->route('surveys_index', app()->getLocale())->with('success', Lang::get('Survey Disabled Successfully'));
    }

    public function open($id)
    {
        try {
            $this->surveyService->canBeOpened($id);
            $this->surveyService->open($id);

        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->route('surveys_index', app()->getLocale())->with('danger', Lang::get('Something goes wrong while opening Survey: ') . Lang::get($exception->getMessage()));
        }
        return redirect()->route('surveys_index', app()->getLocale())->with('success', Lang::get('Survey Opened Successfully'));
    }


    public function close($id)
    {
        try {
            $this->surveyService->close($id);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->route('surveys_index', app()->getLocale())->with('danger', Lang::get('Something goes wrong while closing Survey'));
        }
        return redirect()->route('surveys_index', app()->getLocale())->with('success', Lang::get('Survey closed Successfully'));
    }

    public function archive($id)
    {
        try {
            $this->surveyService->archive($id);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->route('surveys_index', app()->getLocale())->with('danger', Lang::get('Something goes wrong while arciving Survey'));
        }
        return redirect()->route('surveys_index', app()->getLocale())->with('success', Lang::get('Survey arcived Successfully'));

    }

    public function publish($id)
    {
        try {
            $this->surveyService->publish($id);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->route('surveys_index', app()->getLocale())->with('danger', Lang::get('Something goes wrong while publishing Survey'));
        }
        return redirect()->route('surveys_index', app()->getLocale())->with('success', Lang::get('Survey published Successfully'));

    }

    public function unpublish($id)
    {
        try {
            $this->surveyService->unpublish($id);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->route('surveys_index', app()->getLocale())->with('danger', Lang::get('Something goes wrong while un publishing Survey'));
        }
        return redirect()->route('surveys_index', app()->getLocale())->with('success', Lang::get('Survey un published Successfully'));

    }

    public function changeUpdatable($id)
    {
        try {
            $survey = $this->surveyService->getById($id);
            $this->surveyService->changeUpdatable($id, !$survey->updatable);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->route('surveys_index', app()->getLocale())->with('danger', Lang::get('Something goes wrong while updating Survey updatable property'));
        }
        return redirect()->route('surveys_index', app()->getLocale())->with('success', Lang::get('Survey updated Successfully'));
    }


    public function getSurveys()
    {
        return $this->surveyService->getNonArchivedSurveysWithFilters(
            $this->search,
            User::isSuperAdmin()
        );
    }



    public function render()
    {
        $params['surveys'] = $this->getSurveys();
        return view('livewire.survey-index', $params)->extends('layouts.master')->section('content');
    }
}
