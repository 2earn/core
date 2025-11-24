<?php

namespace App\Livewire;

use App\Models\BusinessSector;
use App\Models\TranslaleModel;
use App\Services\BusinessSector\BusinessSectorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class BusinessSectorCreateUpdate extends Component
{

    use WithFileUploads;

    public $idBusinessSector;
    public $name, $description, $color;
    public $thumbnailsImage;
    public $thumbnailsHomeImage;
    public $logoImage;
    public $update = false;
    protected BusinessSectorService $businessSectorService;

    protected $rules = [
        'name' => 'required',
        'description' => 'required',
        'thumbnailsImage' => 'nullable|image|mimes:jpeg,png,jpg',
        'thumbnailsHomeImage' => 'nullable|image|mimes:jpeg,png,jpg',
        'logoImage' => 'nullable|image|mimes:jpeg,png,jpg',
    ];

    public function boot(BusinessSectorService $businessSectorService)
    {
        $this->businessSectorService = $businessSectorService;
    }

    public function mount(Request $request)
    {
        $this->idBusinessSector = $request->input('id');
        if (!is_null($this->idBusinessSector)) {
            $this->edit($this->idBusinessSector);
        }
    }

    public function edit($idBusinessSector)
    {
        $businessSector = $this->businessSectorService->getBusinessSectorByIdOrFail($idBusinessSector);
        $this->idBusinessSector = $idBusinessSector;
        $this->name = $businessSector->name;
        $this->color = $businessSector->color;
        $this->description = $businessSector->description;
        $this->update = true;
    }

    public function cancel()
    {
        return redirect()->route('business_sector_index', ['locale' => app()->getLocale()])->with('warning', Lang::get('Business sector operation cancelled'));
    }
    public function removeImage($property)
    {
        if (property_exists($this, $property)) {
            $this->reset($property);
        }
    }

    public function deleteExistingImage($field)
    {
        $businessSector = $this->businessSectorService->getBusinessSectorById($this->idBusinessSector);

        if ($businessSector) {
            $imageType = match($field) {
                'thumbnailsImage' => BusinessSector::IMAGE_TYPE_THUMBNAILS,
                'thumbnailsHomeImage' => BusinessSector::IMAGE_TYPE_THUMBNAILS_HOME,
                'logoImage' => BusinessSector::IMAGE_TYPE_LOGO,
                default => null,
            };

            if ($imageType) {
                $this->businessSectorService->deleteBusinessSectorImage($businessSector, $imageType);
            }
        }

        $this->{$field} = null;
    }

    public function updateBU()
    {
        $this->validate();

        try {
            $businessSector = $this->businessSectorService->getBusinessSectorByIdOrFail($this->idBusinessSector);

            $this->businessSectorService->updateBusinessSector($this->idBusinessSector, [
                'name' => $this->name,
                'color' => $this->color,
                'description' => $this->description
            ]);

            if ($this->thumbnailsImage) {
                $this->businessSectorService->handleImageUpload(
                    $businessSector,
                    $this->thumbnailsImage,
                    BusinessSector::IMAGE_TYPE_THUMBNAILS
                );
            }

            if ($this->thumbnailsHomeImage) {
                $this->businessSectorService->handleImageUpload(
                    $businessSector,
                    $this->thumbnailsHomeImage,
                    BusinessSector::IMAGE_TYPE_THUMBNAILS_HOME
                );
            }

            if ($this->logoImage) {
                $this->businessSectorService->handleImageUpload(
                    $businessSector,
                    $this->logoImage,
                    BusinessSector::IMAGE_TYPE_LOGO
                );
            }

        } catch (\Exception $exception) {
            $this->cancel();
            Log::error($exception->getMessage());
            return redirect()->route('business_sector_index', ['locale' => app()->getLocale(), 'idBusinessSector' => $this->idBusinessSector])->with('danger', Lang::get('Something goes wrong while updating Business sector'));
        }
        return redirect()->route('business_sector_index', ['locale' => app()->getLocale(), 'idBusinessSector' => $this->idBusinessSector])->with('success', Lang::get('Business sector Updated Successfully'));

    }

    public function storeBU()
    {
        $this->validate();

        try {
            $businessSector = $this->businessSectorService->createBusinessSector([
                'name' => $this->name,
                'description' => $this->description,
                'color' => $this->color
            ]);

            if (!$businessSector) {
                throw new \Exception('Failed to create business sector');
            }

            if ($this->thumbnailsImage) {
                $this->businessSectorService->handleImageUpload(
                    $businessSector,
                    $this->thumbnailsImage,
                    BusinessSector::IMAGE_TYPE_THUMBNAILS
                );
            }

            if ($this->thumbnailsHomeImage) {
                $this->businessSectorService->handleImageUpload(
                    $businessSector,
                    $this->thumbnailsHomeImage,
                    BusinessSector::IMAGE_TYPE_THUMBNAILS_HOME
                );
            }

            if ($this->logoImage) {
                $this->businessSectorService->handleImageUpload(
                    $businessSector,
                    $this->logoImage,
                    BusinessSector::IMAGE_TYPE_LOGO
                );
            }

            createTranslaleModel($businessSector, 'name', $this->name);
            createTranslaleModel($businessSector, 'description', $this->description);

        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->route('business_sector_index', ['locale' => app()->getLocale()])->with('danger', Lang::get('Something goes wrong while creating Business sector'));
        }
        return redirect()->route('business_sector_index', ['locale' => app()->getLocale()])->with('success', Lang::get('Business sector Created Successfully'));
    }

    public function render()
    {
        $params = ['businessSector' => $this->businessSectorService->getBusinessSectorById($this->idBusinessSector)];
        return view('livewire.business-sector-create-update', $params)->extends('layouts.master')->section('content');
    }
}
