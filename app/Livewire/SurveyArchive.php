<?php

namespace App\Livewire;

use App\Models\Survey;
use App\Models\User;
use Core\Enum\StatusSurvey;
use Illuminate\Support\Facades\Route;
use Livewire\Component;
use Livewire\WithPagination;

class SurveyArchive extends Component
{
    use WithPagination;

    public $search = '';
    public $currentRouteName;

    protected $paginationTheme = 'bootstrap';

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
        $archivedSurveys = [];
        $surveysQuery = Survey::where('status', '=', StatusSurvey::ARCHIVED->value);

        if (User::isSuperAdmin()) {

            if (!is_null($this->search) && !empty($this->search)) {
                $surveysQuery = $surveysQuery->where('name', 'like', '%' . $this->search . '%');
            }

        } else {
            if (!is_null($this->search) && !empty($this->search)) {
                $surveysQuery = $surveysQuery
                    ->where('name', 'like', '%' . $this->search . '%');
            }
        }

        $surveys = $surveysQuery->get();
        foreach ($surveys as $survey) {
            if ($survey->canShowAfterArchiving()) {
                $archivedSurveys[] = $survey;
            }
        }
        return $archivedSurveys;
    }

    public function render()
    {
        $params['surveys'] = $this->getArchivedSurveys();
        return view('livewire.survey-archive', $params)->extends('layouts.master')->section('content');
    }
}
