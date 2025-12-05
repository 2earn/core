<?php

namespace App\Livewire;

use App\Models\CommissionFormula;
use App\Services\Commission\CommissionFormulaService;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class CommissionFormulaCreateUpdate extends Component
{
    use WithFileUploads;

    protected $commissionFormulaService;

    public $formulaId = null;
    public $name = '';
    public $initial_commission = '';
    public $final_commission = '';
    public $description = '';
    public $is_active = true;
    public $iconImage;
    public $existingIconUrl = null;

    public $isEditMode = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'initial_commission' => 'required|numeric|min:0|max:100',
        'final_commission' => 'required|numeric|min:0|max:100|gt:initial_commission',
        'description' => 'nullable|string',
        'is_active' => 'boolean',
        'iconImage' => 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:2048',
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
        'iconImage.image' => 'The icon must be an image file.',
        'iconImage.mimes' => 'The icon must be a file of type: jpeg, png, jpg, svg, webp.',
        'iconImage.max' => 'The icon size must not exceed 2MB.',
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

        // Load existing icon image if available
        if ($formula->iconImage) {
            $this->existingIconUrl = $formula->iconImage->url;
        }
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
                // Handle icon image upload for update
                if ($this->iconImage) {
                    $this->handleIconImageUpload($formula);
                }

                session()->flash('success', Lang::get('Commission formula updated successfully.'));
                return redirect()->route('commission_formula_index', ['locale' => app()->getLocale()]);
            } else {
                session()->flash('error', Lang::get('Failed to update commission formula.'));
            }
        } else {
            $formula = $this->commissionFormulaService->createCommissionFormula($data);

            if ($formula) {
                // Handle icon image upload for create
                if ($this->iconImage) {
                    $this->handleIconImageUpload($formula);
                }

                session()->flash('success', Lang::get('Commission formula created successfully.'));
                return redirect()->route('commission_formula_index', ['locale' => app()->getLocale()]);
            } else {
                session()->flash('error', Lang::get('Failed to create commission formula.'));
            }
        }
    }

    /**
     * Handle icon image upload
     */
    private function handleIconImageUpload($formula)
    {
        try {
            // Delete old image if exists
            if ($formula->iconImage) {
                if (Storage::disk('public2')->exists($formula->iconImage->url)) {
                    Storage::disk('public2')->delete($formula->iconImage->url);
                }
                $formula->iconImage()->delete();
            }

            // Store new image
            $imagePath = $this->iconImage->store('commission-formulas/' . CommissionFormula::IMAGE_TYPE_ICON, 'public2');

            // Create image record
            $formula->iconImage()->create([
                'type' => CommissionFormula::IMAGE_TYPE_ICON,
                'url' => $imagePath,
                'created_by' => auth()->id(),
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to upload icon image: ' . $e->getMessage());
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
