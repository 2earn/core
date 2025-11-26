<?php

namespace App\Livewire;

use App\Services\News\NewsService;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Livewire\Component;
use Livewire\WithPagination;

class NewsIndex extends Component
{
    use WithPagination;

    const PAGE_SIZE = 3;
    public $search = '';
    public $currentRouteName;
    protected $paginationTheme = 'bootstrap';
    public $listeners = ['delete' => 'delete', 'duplicateNews' => 'duplicateNews', 'clearDeleteNewsId' => 'clearDeleteNewsId'];
    public $newsIdToDelete = null;

    protected NewsService $newsService;

    public function boot(NewsService $newsService)
    {
        $this->newsService = $newsService;
    }

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

    public function duplicateNews($id)
    {
        try {
            $this->newsService->duplicate($id);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->route('news_index', ['locale' => app()->getLocale()])->with('danger', Lang::get('News Duplication failed'));
        }
        return redirect()->route('news_index', ['locale' => app()->getLocale()])->with('success', Lang::get('News Duplicated Successfully'));
    }

    public function confirmDelete($id)
    {
        $this->newsIdToDelete = $id;
        $this->dispatch('showDeleteModal');
    }

    public function delete()
    {
        try {
            $this->newsService->delete($this->newsIdToDelete);
            $this->newsIdToDelete = null;
            $this->dispatch('hideDeleteModal');
            return redirect()->route('news_index', ['locale' => app()->getLocale()])->with('success', Lang::get('News Deleted Successfully'));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            $this->dispatch('hideDeleteModal');
            return redirect()->route('news_index', ['locale' => app()->getLocale()])->with('danger', $exception->getMessage());
        }
    }

    public function clearDeleteNewsId()
    {
        $this->newsIdToDelete = null;
    }

    public function render()
    {
        $params['newss'] = $this->newsService->getPaginated($this->search, self::PAGE_SIZE);
        return view('livewire.news-index', $params)->extends('layouts.master')->section('content');
    }
}
