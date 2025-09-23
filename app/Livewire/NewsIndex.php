<?php

namespace App\Livewire;

use App\Models\News;
use App\Models\News as ModelsNews;
use App\Services\Communication\Communication;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Livewire\Component;
use Livewire\WithPagination;

class NewsIndex extends Component
{
    use WithPagination;

    const PAGE_SIZE = 5;
    public $search = '';
    public $currentRouteName;
    protected $paginationTheme = 'bootstrap';
    public $listeners = ['delete' => 'delete', 'duplicateNews' => 'duplicateNews', 'clearDeleteNewsId' => 'clearDeleteNewsId'];
    public $newsIdToDelete = null;

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
            Communication::duplicateNews($id);

        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->route('news_index', ['locale' => app()->getLocale()])->with('success', Lang::get('News Duplication failed'));
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
            ModelsNews::findOrFail($this->newsIdToDelete)->delete();
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
        if (!is_null($this->search) && !empty($this->search)) {
            $params['newss'] = News::with(['hashtags', 'mainImage'])
                ->where('title', 'like', '%' . $this->search . '%')
                ->orderBy('created_at', 'desc')
                ->paginate(self::PAGE_SIZE);
        } else {
            $params['newss'] = News::with(['hashtags', 'mainImage'])
                ->orderBy('created_at', 'desc')
                ->paginate(self::PAGE_SIZE);
        }
        return view('livewire.news-index', $params)->extends('layouts.master')->section('content');
    }
}
