<?php

namespace App\Http\Livewire;

use App\Models\Survey;
use Core\Enum\StatusSurvey;
use Illuminate\Support\Facades\Route;
use Livewire\Component;
use Livewire\WithPagination;

class SurveyArchive extends Component
{
    use WithPagination;

    public $search = '';
    public $disableNote = '';
    public $currentRouteName;

    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        $this->currentRouteName = Route::currentRouteName();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function getSurveys()
    {
        $surveysQuery = Survey::where('status', '=', StatusSurvey::ARCHIVED->value);

        if (auth()?->user()?->getRoleNames()->first() == "Super admin") {

            if (!is_null($this->search) && !empty($this->search)) {
                $surveysQuery = $surveysQuery->where('name', 'like', '%' . $this->search . '%');
            }

        } else {
            $surveysQuery = Survey::where('published', true);

            if (!is_null($this->search) && !empty($this->search)) {
                $surveysQuery = $surveysQuery
                    ->where('name', 'like', '%' . $this->search . '%');
            }

        }
        return $surveysQuery->get();
    }

    public function render()
    {
        $params['surveys'] = $this->getSurveys();
        return view('livewire.survey-archive', $params)->extends('layouts.master')->section('content');
    }
}
