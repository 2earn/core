<?php

namespace App\Livewire;

use App\Services\Platform\PlatformService;
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
    public $perPage = 5;

    protected $queryString = ['search'];

    protected PlatformService $platformService;

    public function boot(PlatformService $platformService)
    {
        $this->platformService = $platformService;
    }

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
        $platforms = $this->platformService->getPaginatedPlatforms($this->search, $this->perPage);

        return view('livewire.platform-index', [
            'platforms' => $platforms
        ])->extends('layouts.master')->section('content');
    }
}
