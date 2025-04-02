<?php

namespace App\Livewire;

use App\Models\BusinessSector;
use App\Models\Image;
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
    public $name, $description;
    public $thumbnailsImage;
    public $logoImage;
    public $update = false;

    protected $rules = [
        'name' => 'required',
        'description' => 'required',
    ];

    public function mount(Request $request)
    {
        $this->idBusinessSector = $request->input('id');
        if (!is_null($this->idBusinessSector)) {
            $this->edit($this->idBusinessSector);
        }
    }

    public function edit($idBusinessSector)
    {
        $businessSector = BusinessSector::findOrFail($idBusinessSector);
        $this->idBusinessSector = $idBusinessSector;
        $this->name = $businessSector->name;
        $this->description = $businessSector->description;
        $this->update = true;
    }

    public function cancel()
    {
        return redirect()->route('business_sector_index', ['locale' => app()->getLocale()])->with('warning', Lang::get('Business sector operation cancelled'));
    }

    public function update()
    {
        $this->validate();

        try {
            $businessSector = BusinessSector::where('id', $this->idBusinessSector)->first();
            $businessSector->update(['name' => $this->name, 'description' => $this->description]);
            if ($this->thumbnailsImage) {
                if ($businessSector->thumbnailsImage) {
                    Storage::disk('public2')->delete($businessSector->thumbnailsImage->url);
                }
                $imagePath = $this->thumbnailsImage->store('business-sectors/' . BusinessSector::IMAGE_TYPE_THUMBNAILS, 'public2');
                $businessSector->thumbnailsImage()->delete();
                $businessSector->thumbnailsImage()->create([
                    'url' => $imagePath,
                    'type' => BusinessSector::IMAGE_TYPE_THUMBNAILS,
                ]);
            }
            if ($this->logoImage) {

                if ($businessSector->logoImage) {
                    Storage::disk('public2')->delete($businessSector->logoImage->url);
                }
                $imagePath = $this->logoImage->store('business-sectors/' . BusinessSector::IMAGE_TYPE_LOGO, 'public2');
                $businessSector->logoImage()->delete();
                $businessSector->logoImage()->create([
                    'url' => $imagePath,
                    'type' => BusinessSector::IMAGE_TYPE_LOGO,
                ]);
            }

        } catch (\Exception $exception) {
            $this->cancel();
            Log::error($exception->getMessage());
            return redirect()->route('business_sector_index', ['locale' => app()->getLocale(), 'idBusinessSector' => $this->idBusinessSector])->with('danger', Lang::get('Something goes wrong while updating Business sector'));
        }
        return redirect()->route('business_sector_index', ['locale' => app()->getLocale(), 'idBusinessSector' => $this->idBusinessSector])->with('success', Lang::get('Business sector Updated Successfully'));

    }

    public function store()
    {
        $this->validate();
        $businessSectorData = ['name' => $this->name, 'description' => $this->description];
        try {
            $businessSector = BusinessSector::create($businessSectorData);
            if ($this->thumbnailsImage) {
                $imagePath = $this->thumbnailsImage->store('business-sectors/' . BusinessSector::IMAGE_TYPE_THUMBNAILS, 'public2');
                $businessSector->thumbnailsImage()->create([
                    'url' => $imagePath,
                    'type' => BusinessSector::IMAGE_TYPE_THUMBNAILS,
                ]);
            }
            if ($this->logoImage) {
                $imagePath = $this->logoImage->store('business-sectors/' . BusinessSector::IMAGE_TYPE_LOGO, 'public2');
                $businessSector->logoImage()->create([
                    'url' => $imagePath,
                    'type' => BusinessSector::IMAGE_TYPE_LOGO,
                ]);
            }
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->route('business_sector_index', ['locale' => app()->getLocale()])->with('danger', Lang::get('Something goes wrong while creating Business sector'));
        }
        return redirect()->route('business_sector_index', ['locale' => app()->getLocale()])->with('success', Lang::get('Business sector Created Successfully'));
    }

    public function render()
    {
        $params = ['businessSector' => BusinessSector::find($this->idBusinessSector)];
        return view('livewire.business-sector-create-update', $params)->extends('layouts.master')->section('content');
    }
}
