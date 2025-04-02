<?php

namespace App\Livewire;

use App\Models\Faq;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Route;
use Livewire\Component;
use Livewire\WithPagination;

class FaqIndex extends Component
{
    use WithPagination;

    const PAGE_SIZE = 5;
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

    public function deleteFaq($idFaq)
    {
        Faq::findOrFail($idFaq)->delete();
        return redirect()->route('faq_index', ['locale' => app()->getLocale()])->with('success', Lang::get('Faq Deleted Successfully'));
    }

    public function render()
    {
        if (!is_null($this->search) && !empty($this->search)) {
            $params['faqs'] = Faq::where('question', 'like', '%' . $this->search . '%')->orderBy('created_at', 'desc')->paginate(self::PAGE_SIZE);
        } else {
            $params['faqs'] = Faq::orderBy('created_at', 'desc')->paginate(self::PAGE_SIZE);
        }
        return view('livewire.faq-index', $params)->extends('layouts.master')->section('content');
    }
}
