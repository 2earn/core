<?php

namespace App\Livewire;

use App\Services\Faq\FaqService;
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

    protected FaqService $faqService;

    public function boot(FaqService $faqService)
    {
        $this->faqService = $faqService;
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

    public function deleteFaq($idFaq)
    {
        $this->faqService->delete($idFaq);
        return redirect()->route('faq_index', ['locale' => app()->getLocale()])->with('success', Lang::get('Faq Deleted Successfully'));
    }

    public function render()
    {
        $params['faqs'] = $this->faqService->getPaginated($this->search, self::PAGE_SIZE);
        return view('livewire.faq-index', $params)->extends('layouts.master')->section('content');
    }
}
