<?php

namespace App\Http\Livewire;

use App\Models\Target;
use App\Models\TargetGroup;
use Illuminate\Support\Facades\Lang;
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
        Target::findOrFail($idTarget)->delete();
        return redirect()->route('target_show', ['locale' => app()->getLocale(), 'idTarget' =>$idTarget])->with('success', Lang::get('Target Deleted Successfully!!'));

    }

    public function removeGroup($idGroup)
    {
        TargetGroup::findOrFail($idGroup)->delete();
        return redirect()->route('target_show', ['locale' => app()->getLocale(), 'idTarget' => $this->idTarget])->with('success', Lang::get('Group Deleted Successfully!!'));
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
