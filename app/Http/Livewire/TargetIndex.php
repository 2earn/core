<?php

namespace App\Http\Livewire;

use App\Models\Target;
use Livewire\Component;
use Livewire\WithPagination;

class TargetIndex extends Component
{
    use WithPagination;

    public $search = '';
    protected $paginationTheme = 'bootstrap';

    public function resetPage($pageName = 'page')
    {
        $this->setPage(1, $pageName);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function deleteTarget($idTarget)
    {
        dd($idTarget);
    }

    public function render()
    {
        if (!is_null($this->search) && !empty($this->search)) {
            $params['targets'] = Target::where('name', 'like', '%' . $this->search . '%')->paginate(3);
        } else {
            $params['targets'] = Target::paginate(3);
        }
        return view('livewire.target-index', $params)->extends('layouts.master')->section('content');
    }
}
