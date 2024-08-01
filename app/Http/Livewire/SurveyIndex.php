<?php

namespace App\Http\Livewire;

use App\Models\Survey;
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
    public function render()
    {
        $params['surveys'] = Survey::where('name', 'like', '%'.$this->search.'%')->paginate(3);
        return view('livewire.survey-index', $params)->extends('layouts.master')->section('content');
    }
}
