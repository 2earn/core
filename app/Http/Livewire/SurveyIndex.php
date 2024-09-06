<?php

namespace App\Http\Livewire;

use App\Models\Survey;
use Core\Enum\StatusSurvey;
use Illuminate\Support\Facades\Lang;
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
            Survey::enable($id);
        } catch (\Exception $exception) {
            return redirect()->route('surveys_index', app()->getLocale())->with('danger', Lang::get('Something goes wrong while Enabling Survey!!') );
        }
        return redirect()->route('surveys_index', app()->getLocale())->with('success', Lang::get('Survey Enabled Successfully!!'));
    }

    public function disable($id)
    {
        try {
            if (empty($this->disableNote)) {
                throw new \Exception(Lang::get('Something goes wrong while Disabling Survey!!'));
            }
            Survey::disable($id, $this->disableNote);
        } catch (\Exception $exception) {
            return redirect()->route('surveys_index', app()->getLocale())->with('danger', Lang::get('Something goes wrong while Disabling Survey!!') );
        }

        return redirect()->route('surveys_index', app()->getLocale())->with('success', Lang::get('Survey Disabled Successfully!!'));
    }

    public function open($id)
    {
        try {
            Survey::canBeOpened($id);
            Survey::open($id);

        } catch (\Exception $exception) {
            return redirect()->route('surveys_index', app()->getLocale())->with('danger', Lang::get('Something goes wrong while opening Survey: ').Lang::get($exception->getMessage()) );
        }
        return redirect()->route('surveys_index', app()->getLocale())->with('success', Lang::get('Survey Opened Successfully!!'));
    }


    public function close($id)
    {
        try {
            Survey::close($id);
        } catch (\Exception $exception) {
            return redirect()->route('surveys_index', app()->getLocale())->with('danger', Lang::get('Something goes wrong while closing Survey!!') );
        }
        return redirect()->route('surveys_index', app()->getLocale())->with('success', Lang::get('Survey closed Successfully!!'));

    }

    public function archive($id)
    {
        try {
            Survey::archive($id);
        } catch (\Exception $exception) {
            return redirect()->route('surveys_index', app()->getLocale())->with('danger', Lang::get('Something goes wrong while arciving Survey!!') );
        }
        return redirect()->route('surveys_index', app()->getLocale())->with('success', Lang::get('Survey arcived Successfully!!'));

    }

    public function publish($id)
    {
        try {
            Survey::publish($id);
        } catch (\Exception $exception) {
            return redirect()->route('surveys_index', app()->getLocale())->with('danger', Lang::get('Something goes wrong while publishing Survey!!') );
        }
        return redirect()->route('surveys_index', app()->getLocale())->with('success', Lang::get('Survey published Successfully!!'));

    }

    public function unpublish($id)
    {
        try {
            Survey::unpublish($id);
        } catch (\Exception $exception) {
            return redirect()->route('surveys_index', app()->getLocale())->with('danger', Lang::get('Something goes wrong while un publishing Survey!!') );
        }
        return redirect()->route('surveys_index', app()->getLocale())->with('success', Lang::get('Survey un published Successfully!!'));

    }

    public function changeUpdatable($id)
    {
        try {
            $survey = Survey::find($id);
            Survey::changeUpdatable($id, !$survey->updatable);
        } catch (\Exception $exception) {
            return redirect()->route('surveys_index', app()->getLocale())->with('danger', Lang::get('Something goes wrong while updating Survey updatable property!!') );
        }
        return redirect()->route('surveys_index', app()->getLocale())->with('success', Lang::get('Survey updatedd Successfully!!'));
    }


    public function getSurveys()
    {
        $surveys = [];
        $surveysQuery = Survey::where('status', '!=', StatusSurvey::ARCHIVED->value);

        if (strtoupper(auth()?->user()?->getRoleNames()->first()) == Survey::SUPER_ADMIN_ROLE_NAME) {

            if (!is_null($this->search) && !empty($this->search)) {
                $surveysQuery = $surveysQuery->where('name', 'like', '%' . $this->search . '%');
            }

        } else {

            $surveysQuery = $surveysQuery->where('published', true)
                ->where('status', '!=', StatusSurvey::NEW->value);

            if (!is_null($this->search) && !empty($this->search)) {
                $surveysQuery = $surveysQuery
                    ->where('name', 'like', '%' . $this->search . '%');
            }

        }

        if (strtoupper(auth()?->user()?->getRoleNames()->first()) == Survey::SUPER_ADMIN_ROLE_NAME) {
            return $surveysQuery->get();
        }

        foreach ($surveysQuery->get() as $survey) {
            if ($survey->canShow()) {
                $surveys[] = $survey;
            }
        }
        return $surveys;
    }

    public function render()
    {
        $params['surveys'] = $this->getSurveys();
        return view('livewire.survey-index', $params)->extends('layouts.master')->section('content');
    }
}
