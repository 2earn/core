<?php

namespace App\Livewire;

use App\Services\Commission\PlanLabelService;
use Livewire\Component;
use Livewire\WithPagination;

class PlanLabelIndex extends Component
{
    use WithPagination;

    protected $planLabelService;

    public $search = '';
    public $filterActive = '';
    public $filterStars = '';
    public $sortBy = 'created_at';
    public $sortDirection = 'desc';

    public $showDeleteModal = false;
    public $deleteId = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'filterActive' => ['except' => ''],
        'filterStars' => ['except' => ''],
        'sortBy' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
    ];

    protected $listeners = [
        'refreshList' => '$refresh',
    ];

    public function boot(PlanLabelService $planLabelService)
    {
        $this->planLabelService = $planLabelService;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterActive()
    {
        $this->resetPage();
    }

    public function updatingFilterStars()
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
        $success = $this->planLabelService->toggleActive($id);

        if ($success) {
            session()->flash('success', __('Plan label status updated successfully.'));
        } else {
            session()->flash('error', __('Failed to update plan label status.'));
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

    public function deleteLabel()
    {
        if ($this->deleteId) {
            $success = $this->planLabelService->deletePlanLabel($this->deleteId);

            if ($success) {
                session()->flash('success', __('Plan label deleted successfully.'));
            } else {
                session()->flash('error', __('Failed to delete plan label.'));
            }

            $this->deleteId = null;
            $this->showDeleteModal = false;
        }
    }

    public function clearFilters()
    {
        $this->reset(['search', 'filterActive', 'filterStars', 'sortBy', 'sortDirection']);
        $this->resetPage();
    }

    public function render()
    {
        $filters = [
            'search' => $this->search,
            'order_by' => $this->sortBy,
            'order_direction' => $this->sortDirection,
            'with' => ['iconImage'],
        ];

        if ($this->filterActive !== '') {
            $filters['is_active'] = (bool) $this->filterActive;
        }

        if ($this->filterStars !== '') {
            $filters['stars'] = (int) $this->filterStars;
        }

        $labels = $this->planLabelService->getPlanLabels($filters);
        $statistics = $this->planLabelService->getStatistics();

        return view('livewire.plan-label-index', [
            'labels' => $labels,
            'statistics' => $statistics,
        ])->extends('layouts.master')->section('content');
    }
}

