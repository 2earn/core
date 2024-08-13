<?php

namespace App\Http\Livewire;

use App\Models\Survey;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Route;
use Livewire\Component;
use Livewire\WithPagination;

class SurveyIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $disableNote = '';
    const ITEM_PER_PAGE = 5;

    public $currentRouteName;
    protected $paginationTheme = 'bootstrap';

    public function updatingSearch()
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
            return redirect()->route('surveys_index', app()->getLocale())->with('success', Lang::get('Survey Enabled Successfully!!'));
        } catch (\Exception $exception) {
            return redirect()->route('surveys_index', app()->getLocale())->with('danger', Lang::get('Something goes wrong while Enabling Survey!!') . ' : ' . $exception->getMessage());
        }
    }

    public function disable($id)
    {
        try {
            if (!empty($this->disableNote)) {
                Survey::disable($id, $this->disableNote);
            } else {
                return redirect()->route('surveys_index', app()->getLocale())->with('danger', Lang::get('Something goes wrong while Disabling Survey!!') . ' : ');
            }
        } catch (\Exception $exception) {
            return redirect()->route('surveys_index', app()->getLocale())->with('danger', Lang::get('Something goes wrong while Disabling Survey!!') . ' : ' . $exception->getMessage());
        }
        return redirect()->route('surveys_index', app()->getLocale())->with('success', Lang::get('Survey Disabled Successfully!!'));
    }

    public function open($id)
    {
        try {
            if (Survey::canBeOpened($id)) {
                Survey::open($id);
            } else {
                return redirect()->route('surveys_index', app()->getLocale())->with('danger', Lang::get('Something goes wrong while opening Survey!!'));
            }
        } catch (\Exception $exception) {
            return redirect()->route('surveys_index', app()->getLocale())->with('danger', Lang::get('Something goes wrong while opening Survey!!') . ' : ' . $exception->getMessage());
        }
        return redirect()->route('surveys_index', app()->getLocale())->with('success', Lang::get('Survey Opened Successfully!!'));
    }


    public function close($id)
    {
        try {
            Survey::close($id);
            return redirect()->route('surveys_index', app()->getLocale())->with('success', Lang::get('Survey closed Successfully!!'));
        } catch (\Exception $exception) {
            return redirect()->route('surveys_index', app()->getLocale())->with('danger', Lang::get('Something goes wrong while closing Survey!!') . ' : ' . $exception->getMessage());
        }
    }

    public function archive($id)
    {
        try {
            Survey::archive($id);
            return redirect()->route('surveys_index', app()->getLocale())->with('success', Lang::get('Survey arcived Successfully!!'));
        } catch (\Exception $exception) {
            return redirect()->route('surveys_index', app()->getLocale())->with('danger', Lang::get('Something goes wrong while arciving Survey!!') . ' : ' . $exception->getMessage());
        }
    }

    public function publish($id)
    {
        try {
            Survey::publish($id);
            return redirect()->route('surveys_index', app()->getLocale())->with('success', Lang::get('Survey published Successfully!!'));
        } catch (\Exception $exception) {
            return redirect()->route('surveys_index', app()->getLocale())->with('danger', Lang::get('Something goes wrong while publishing Survey!!') . ' : ' . $exception->getMessage());
        }
    }

    public function unpublish($id)
    {
        try {
            Survey::unpublish($id);
            return redirect()->route('surveys_index', app()->getLocale())->with('success', Lang::get('Survey un published Successfully!!'));
        } catch (\Exception $exception) {
            return redirect()->route('surveys_index', app()->getLocale())->with('danger', Lang::get('Something goes wrong while un publishing Survey!!') . ' : ' . $exception->getMessage());
        }
    }

    public function getSurveys()
    {
        if (auth()?->user()?->getRoleNames()->first() == "Super admin") {
            if (!is_null($this->search) && !empty($this->search)) {
                $surveys = Survey::where('name', 'like', '%' . $this->search . '%');
            } else {
                $surveys = Survey::whereNotNull('name');
            }
        } else {
            $surveys = Survey::where('enabled', true);
            if (!is_null($this->search) && !empty($this->search)) {
                $surveys = $surveys->where('published', true)
                    ->where('name', 'like', '%' . $this->search . '%');
            }
        }
        return $surveys->paginate(self::ITEM_PER_PAGE);
    }

    public function render()
    {
        $params['surveys'] = $this->getSurveys();
        return view('livewire.survey-index', $params)->extends('layouts.master')->section('content');
    }
}
