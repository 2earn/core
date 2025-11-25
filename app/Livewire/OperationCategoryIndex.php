<?php

namespace App\Livewire;

use App\Services\Balances\OperationCategoryService;
use Livewire\Component;
use Livewire\WithPagination;

class OperationCategoryIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    protected OperationCategoryService $operationCategoryService;

    protected $paginationTheme = 'bootstrap';

    public function __construct()
    {
        $this->operationCategoryService = app(OperationCategoryService::class);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $categories = $this->operationCategoryService->getFilteredCategories(
            $this->search,
            $this->perPage
        );

        return view('livewire.operation-category-index', [
            'categories' => $categories
        ])->extends('layouts.master')->section('content');
    }
}
