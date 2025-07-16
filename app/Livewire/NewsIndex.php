<?php

namespace App\Livewire;

use App\Models\News;
use App\Models\News as ModelsNews;
use App\Models\TranslaleModel;
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
    public $listeners = ['delete' => 'delete', 'duplicateNews' => 'duplicateNews'];

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
        $original = News::findOrFail($id);
        $duplicate = $original->replicate();
        $duplicate->title = $original->title . ' (Copy)';
        $duplicate->content = $original->content . ' (Copy)';
        $duplicate->enabled = false;
        $duplicate->created_at = now();
        $duplicate->updated_at = now();
        $duplicate->save();
        $translations = ['title', 'content'];

        foreach ($translations as $translation) {
            TranslaleModel::create(
                [
                    'name' => TranslaleModel::getTranslateName($duplicate, $translation),
                    'value' => $duplicate->{$translation} . ' AR',
                    'valueFr' => $duplicate->{$translation} . ' FR',
                    'valueEn' => $duplicate->{$translation} . ' EN',
                    'valueTr' => $duplicate->{$translation} . ' TR',
                    'valueEs' => $duplicate->{$translation} . ' ES',
                    'valueRu' => $duplicate->{$translation} . ' Ru',
                    'valueDe' => $duplicate->{$translation} . ' De',
                ]);
        }
        if (!is_null($original->mainImage)) {
            $image = $original->mainImage->replicate();
            $image->imageable_id = $duplicate->id;
            $image->save();
        }
        return redirect()->route('news_index', ['locale' => app()->getLocale()])->with('success', Lang::get('News Duplicated Successfully'));

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
