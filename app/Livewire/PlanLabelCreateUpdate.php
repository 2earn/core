<?php

namespace App\Livewire;

use App\Models\PlanLabel;
use App\Services\Commission\PlanLabelService;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class PlanLabelCreateUpdate extends Component
{
    use WithFileUploads;

    protected $planLabelService;

    public $labelId = null;
    public $name = '';
    public $step = '';
    public $rate = '';
    public $stars = '';
    public $initial_commission = '';
    public $final_commission = '';
    public $description = '';
    public $is_active = true;
    public $iconImage;
    public $existingIconUrl = null;

    public $isEditMode = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'step' => 'nullable|integer',
        'rate' => 'nullable|numeric|min:0',
        'stars' => 'nullable|integer|min:1|max:5',
        'initial_commission' => 'required|numeric|min:0|max:100',
        'final_commission' => 'required|numeric|min:0|max:100|gt:initial_commission',
        'description' => 'nullable|string',
        'is_active' => 'boolean',
        'iconImage' => 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:2048',
    ];

    protected $messages = [
        'name.required' => 'The name field is required.',
        'name.max' => 'The name must not exceed 255 characters.',
        'step.integer' => 'The step must be an integer.',
        'rate.numeric' => 'The rate must be a number.',
        'rate.min' => 'The rate must be at least 0.',
        'stars.integer' => 'The stars must be an integer.',
        'stars.min' => 'The stars must be at least 1.',
        'stars.max' => 'The stars must not exceed 5.',
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

    public function boot(PlanLabelService $planLabelService)
    {
        $this->planLabelService = $planLabelService;
    }

    public function mount($id = null)
    {
        if ($id) {
            $this->isEditMode = true;
            $this->labelId = $id;
            $this->loadLabel($id);
        }
    }

    public function loadLabel($id)
    {
        $label = $this->planLabelService->getPlanLabelById($id);

        if (!$label) {
            session()->flash('error', Lang::get('Plan label not found.'));
            return redirect()->route('plan_label_index', ['locale' => app()->getLocale()]);
        }

        $this->name = $label->name;
        $this->step = $label->step;
        $this->rate = $label->rate;
        $this->stars = $label->stars;
        $this->initial_commission = $label->initial_commission;
        $this->final_commission = $label->final_commission;
        $this->description = $label->description;
        $this->is_active = $label->is_active;

        if ($label->iconImage) {
            $this->existingIconUrl = $label->iconImage->url;
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
            'step' => $this->step ?: null,
            'rate' => $this->rate ?: null,
            'stars' => $this->stars ?: null,
            'initial_commission' => $this->initial_commission,
            'final_commission' => $this->final_commission,
            'description' => $this->description,
            'is_active' => $this->is_active,
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
        ];

        try {
            if ($this->isEditMode) {
                $label = $this->planLabelService->updatePlanLabel($this->labelId, $data);

                if ($label) {
                    if ($this->iconImage) {
                        $this->handleIconImageUpload($label);
                    }

                    session()->flash('success', Lang::get('Plan label updated successfully.'));
                    return redirect()->route('plan_label_index', ['locale' => app()->getLocale()]);
                } else {
                    session()->flash('error', Lang::get('Failed to update plan label.'));
                }
            } else {
                $label = $this->planLabelService->createPlanLabel($data);

                if ($label) {
                    if ($this->iconImage) {
                        $this->handleIconImageUpload($label);
                    }

                    session()->flash('success', Lang::get('Plan label created successfully.'));
                    return redirect()->route('plan_label_index', ['locale' => app()->getLocale()]);
                } else {
                    session()->flash('error', Lang::get('Failed to create plan label.'));
                }
            }
        } catch (\Exception $e) {
            Log::error('Error saving plan label: ' . $e->getMessage());
            session()->flash('error', Lang::get('An error occurred while saving the plan label.'));
        }

        return redirect()->route('plan_label_index', ['locale' => app()->getLocale()]);
    }

    private function handleIconImageUpload($label)
    {
        try {
            if ($label->iconImage) {
                if (Storage::disk('public2')->exists($label->iconImage->url)) {
                    Storage::disk('public2')->delete($label->iconImage->url);
                }
                $label->iconImage()->delete();
            }

            $imagePath = $this->iconImage->store('plan-labels/' . PlanLabel::IMAGE_TYPE_ICON, 'public2');

            $label->iconImage()->create([
                'type' => PlanLabel::IMAGE_TYPE_ICON,
                'url' => $imagePath,
                'created_by' => auth()->id(),
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to upload icon image: ' . $e->getMessage());
        }
    }

    public function cancel()
    {
        return redirect()->route('plan_label_index', ['locale' => app()->getLocale()]);
    }

    public function render()
    {
        return view('livewire.plan-label-create-update')
            ->extends('layouts.master')
            ->section('content');
    }
}

