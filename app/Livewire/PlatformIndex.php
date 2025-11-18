<?php

namespace App\Livewire;

use Core\Models\Platform as ModelsPlatform;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;

class PlatformIndex extends Component
{
    use WithPagination;

    public $listeners = ['delete' => 'delete'];
    public $search = '';
    public $perPage = 10;

    protected $queryString = ['search'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function delete($id)
    {
        try {
            ModelsPlatform::findOrFail($id)->delete();
            return redirect()->route('platform_index', ['locale' => app()->getLocale()])->with('success', Lang::get('Platform Deleted Successfully'));
        }catch (\Exception $exception){
            Log::error($exception->getMessage());
            return redirect()->route('platform_index', ['locale' => app()->getLocale()])->with('danger', $exception->getMessage());
        }
    }

    public function render()
    {
        $platforms = ModelsPlatform::with(['businessSector', 'pendingTypeChangeRequest'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('type', 'like', '%' . $this->search . '%')
                      ->orWhere('id', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        return view('livewire.platform-index', [
            'platforms' => $platforms
        ])->extends('layouts.master')->section('content');
    }
}
