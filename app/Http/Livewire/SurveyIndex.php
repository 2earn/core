<?php

namespace App\Http\Livewire;

use App\Models\Survey;
use Core\Enum\StatusSurvey;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Lang;
use Livewire\Component;
use Livewire\WithPagination;

class SurveyIndex extends Component
{
    use WithPagination;

    public $search = '';
    protected $paginationTheme = 'bootstrap';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function enable($id)
    {
        try {
            Survey::where('id', $id)
                ->update(['enabled' => false, 'enableDate' => Carbon::now(),]);
            return redirect()->route('surveys_index', app()->getLocale())->with('success', Lang::get('Survey Enabled Successfully!!'));
        } catch (\Exception $exception) {
            return redirect()->route('surveys_index', app()->getLocale())->with('danger', Lang::get('Something goes wrong while Enabling Survey!!') . ' : ' . $exception->getMessage());
        }
    }
    public function disable($id)
    {
        try {
            Survey::where('id', $id)
                ->update(['enabled' => false, 'disabledate' => Carbon::now(),]);
            return redirect()->route('surveys_index', app()->getLocale())->with('success', Lang::get('Survey Disabled Successfully!!'));
        } catch (\Exception $exception) {
            return redirect()->route('surveys_index', app()->getLocale())->with('danger', Lang::get('Something goes wrong while Disabling Survey!!') . ' : ' . $exception->getMessage());
        }
    }

    public function open($id)
    {
        try {
            Survey::where('id', $id)
                ->update(['status' => StatusSurvey::OPEN->value, 'openDate' => Carbon::now(),]);
            return redirect()->route('surveys_index', app()->getLocale())->with('success', Lang::get('Survey Opened Successfully!!'));
        } catch (\Exception $exception) {
            return redirect()->route('surveys_index', app()->getLocale())->with('danger', Lang::get('Something goes wrong while opening Survey!!') . ' : ' . $exception->getMessage());
        }
    }

    public function close($id)
    {
        try {
            Survey::where('id', $id)
                ->update(['status' => StatusSurvey::CLOSED->value, 'openDate' => Carbon::now(),]);
            return redirect()->route('surveys_index', app()->getLocale())->with('success', Lang::get('Survey closed Successfully!!'));
        } catch (\Exception $exception) {
            return redirect()->route('surveys_index', app()->getLocale())->with('danger', Lang::get('Something goes wrong while closing Survey!!') . ' : ' . $exception->getMessage());
        }
    }

    public function archive($id)
    {
        try {
            Survey::where('id', $id)
                ->update(['status' => StatusSurvey::ARCHIVED->value, 'openDate' => Carbon::now(),]);
            return redirect()->route('surveys_index', app()->getLocale())->with('success', Lang::get('Survey arcived Successfully!!'));
        } catch (\Exception $exception) {
            return redirect()->route('surveys_index', app()->getLocale())->with('danger', Lang::get('Something goes wrong while arciving Survey!!') . ' : ' . $exception->getMessage());
        }
    }

    public function render()
    {
        $params['surveys'] = Survey::where('name', 'like', '%' . $this->search . '%')->paginate(3);
        return view('livewire.survey-index', $params)->extends('layouts.master')->section('content');
    }
}
