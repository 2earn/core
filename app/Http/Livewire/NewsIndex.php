<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Route;
use App\Models\News;
use App\Models\News as ModelsNews;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;

class NewsIndex extends Component
{
    use WithPagination;

    const PAGE_SIZE = 5;
    public $search = '';
    public $currentRouteName;
    protected $paginationTheme = 'bootstrap';
    public $listeners = ['delete' => 'delete'];

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

    public function delete($id)
    {
        try {
            ModelsNews::findOrFail($id)->delete();
            return redirect()->route('news_index', ['locale' => app()->getLocale()])->with('success', Lang::get('News Deleted Successfully'));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->route('news_index', ['locale' => app()->getLocale()])->with('danger', $exception->getMessage());
        }
    }


    public function render()
    {
        if (!is_null($this->search) && !empty($this->search)) {
            $params['newss'] = News::where('title', 'like', '%' . $this->search . '%')->orderBy('created_at', 'desc')->paginate(self::PAGE_SIZE);
        } else {
            $params['newss'] = News::orderBy('created_at', 'desc')->paginate(self::PAGE_SIZE);
        }
        return view('livewire.news-index', $params)->extends('layouts.master')->section('content');
    }
}
