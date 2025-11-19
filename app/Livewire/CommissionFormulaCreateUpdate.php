<?php

namespace App\Livewire;

use App\Models\CommissionFormula;
use App\Services\Commission\CommissionFormulaService;
use Illuminate\Support\Facades\Lang;
use Livewire\Component;

class CommissionFormulaCreateUpdate extends Component
{
    protected $commissionFormulaService;

    // Form fields
    public $formulaId = null;
    public $name = '';
    public $initial_commission = '';
    public $final_commission = '';
    public $description = '';
    public $is_active = true;

    // State
    public $isEditMode = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'initial_commission' => 'required|numeric|min:0|max:100',
        'final_commission' => 'required|numeric|min:0|max:100|gt:initial_commission',
        'description' => 'nullable|string',
        'is_active' => 'boolean',
    ];

    protected $messages = [
        'name.required' => 'The name field is required.',
        'name.max' => 'The name must not exceed 255 characters.',
        'initial_commission.required' => 'The initial commission field is required.',
        'initial_commission.numeric' => 'The initial commission must be a number.',
        'initial_commission.min' => 'The initial commission must be at least 0.',
        'initial_commission.max' => 'The initial commission must not exceed 100.',
        'final_commission.required' => 'The final commission field is required.',
        'final_commission.numeric' => 'The final commission must be a number.',
        'final_commission.min' => 'The final commission must be at least 0.',
        'final_commission.max' => 'The final commission must not exceed 100.',
        'final_commission.gt' => 'The final commission must be greater than initial commission.',
    ];

    public function boot(CommissionFormulaService $commissionFormulaService)
    {
        $this->commissionFormulaService = $commissionFormulaService;
    }

    public function mount($id = null)
    {
        if ($id) {
            $this->isEditMode = true;
            $this->formulaId = $id;
            $this->loadFormula($id);
        }
    }

    public function loadFormula($id)
    {
        $formula = $this->commissionFormulaService->getCommissionFormulaById($id);

        if (!$formula) {
            session()->flash('error', Lang::get('Commission formula not found.'));
            return redirect()->route('commission_formula_index', ['locale' => app()->getLocale()]);
        }

        $this->name = $formula->name;
        $this->initial_commission = $formula->initial_commission;
        $this->final_commission = $formula->final_commission;
        $this->description = $formula->description;
        $this->is_active = $formula->is_active;
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function save()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'initial_commission' => $this->initial_commission,
            'final_commission' => $this->final_commission,
            'description' => $this->description,
            'is_active' => $this->is_active,
        ];

        if ($this->isEditMode) {
            $formula = $this->commissionFormulaService->updateCommissionFormula($this->formulaId, $data);

            if ($formula) {
                session()->flash('success', Lang::get('Commission formula updated successfully.'));
                return redirect()->route('commission_formula_index', ['locale' => app()->getLocale()]);
            } else {
                session()->flash('error', Lang::get('Failed to update commission formula.'));
            }
        } else {
            $formula = $this->commissionFormulaService->createCommissionFormula($data);

            if ($formula) {
                session()->flash('success', Lang::get('Commission formula created successfully.'));
                return redirect()->route('commission_formula_index', ['locale' => app()->getLocale()]);
            } else {
                session()->flash('error', Lang::get('Failed to create commission formula.'));
            }
        }
    }

    public function cancel()
    {
        return redirect()->route('commission_formula_index', ['locale' => app()->getLocale()]);
    }

    public function render()
    {
        return view('livewire.commission-formula-create-update')
            ->extends('layouts.master')
            ->section('content');
    }
}
