<?php

namespace App\Livewire;

use App\Models\Condition;
use App\Models\Group;
use App\Models\Target;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Route;
use Livewire\Component;
use Livewire\WithPagination;

class TargetIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $currentRouteName;
    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        $this->currentRouteName = Route::currentRouteName();
    }

    public function resetPage($pageName = 'page')
    {
        $this->setPage(1, $pageName);
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function deleteTarget($idTarget)
    {
        Target::findOrFail($idTarget)->delete();
        return redirect()->route('target_index', ['locale' => app()->getLocale()])->with('success', Lang::get('Target Deleted Successfully'));
    }

    public function removeGroup($idGroup, $idTarget)
    {
        Group::findOrFail($idGroup)->delete();
        return redirect()->route('target_show', ['locale' => app()->getLocale(), 'idTarget' => $idTarget])->with('success', Lang::get('Group Deleted Successfully'));
    }

    public function removeCondition($idCondition, $idTarget)
    {
        Condition::findOrFail($idCondition)->delete();
        return redirect()->route('target_show', ['locale' => app()->getLocale(), 'idTarget' => $idTarget])->with('success', Lang::get('Condition Deleted Successfully'));
    }


    public function render()
    {
        if (!is_null($this->search) && !empty($this->search)) {
            $params['targets'] = Target::where('name', 'like', '%' . $this->search . '%')->orderBy('created_at', 'desc')->paginate(3);
        } else {
            $params['targets'] = Target::orderBy('created_at', 'desc')->paginate(3);
        }
        return view('livewire.target-index', $params)->extends('layouts.master')->section('content');
    }
}
