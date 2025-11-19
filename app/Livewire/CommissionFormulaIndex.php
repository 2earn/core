<?php

namespace App\Livewire;

use App\Services\Commission\CommissionFormulaService;
use Livewire\Component;
use Livewire\WithPagination;

class CommissionFormulaIndex extends Component
{
    use WithPagination;

    protected $commissionFormulaService;

    // Filters
    public $search = '';
    public $filterActive = '';
    public $sortBy = 'created_at';
    public $sortDirection = 'desc';

    // Modal state
    public $showDeleteModal = false;
    public $deleteId = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'filterActive' => ['except' => ''],
        'sortBy' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
    ];

    protected $listeners = [
        'refreshList' => '$refresh',
    ];

    public function boot(CommissionFormulaService $commissionFormulaService)
    {
        $this->commissionFormulaService = $commissionFormulaService;
    }

    public function mount()
    {
        // Initialize component
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterActive()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function toggleActive($id)
    {
        $success = $this->commissionFormulaService->toggleActive($id);

        if ($success) {
            session()->flash('success', __('Commission formula status updated successfully.'));
        } else {
            session()->flash('error', __('Failed to update commission formula status.'));
        }
    }

    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->showDeleteModal = true;
    }

    public function cancelDelete()
    {
        $this->deleteId = null;
        $this->showDeleteModal = false;
    }

    public function deleteFormula()
    {
        if ($this->deleteId) {
            $success = $this->commissionFormulaService->deleteCommissionFormula($this->deleteId);

            if ($success) {
                session()->flash('success', __('Commission formula deleted successfully.'));
            } else {
                session()->flash('error', __('Failed to delete commission formula.'));
            }

            $this->deleteId = null;
            $this->showDeleteModal = false;
        }
    }

    public function clearFilters()
    {
        $this->reset(['search', 'filterActive', 'sortBy', 'sortDirection']);
        $this->resetPage();
    }

    public function render()
    {
        $filters = [
            'search' => $this->search,
            'order_by' => $this->sortBy,
            'order_direction' => $this->sortDirection,
        ];

        if ($this->filterActive !== '') {
            $filters['is_active'] = (bool) $this->filterActive;
        }

        $formulas = $this->commissionFormulaService->getCommissionFormulas($filters);
        $statistics = $this->commissionFormulaService->getStatistics();

        return view('livewire.commission-formula-index', [
            'formulas' => $formulas,
            'statistics' => $statistics,
        ])->extends('layouts.master')->section('content');
    }
}
