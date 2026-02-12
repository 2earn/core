<?php

namespace App\Livewire;

use App\Models\User;
use App\Services\Survey\SurveyService;
use Illuminate\Support\Facades\Route;
use Livewire\Component;
use Livewire\WithPagination;

class SurveyArchive extends Component
{
    use WithPagination;

    public $search = '';
    public $currentRouteName;

    protected $paginationTheme = 'bootstrap';

    protected SurveyService $surveyService;

    public function boot(SurveyService $surveyService)
    {
        $this->surveyService = $surveyService;
    }

    public function mount()
    {
        $this->currentRouteName = Route::currentRouteName();
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function getArchivedSurveys()
    {
        return $this->surveyService->getArchivedSurveys(
            $this->search,
            User::isSuperAdmin()
        );
    }

    public function render()
    {
        $params['surveys'] = $this->getArchivedSurveys();
        return view('livewire.survey-archive', $params)->extends('layouts.master')->section('content');
    }
}
