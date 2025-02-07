<?php

namespace App\Http\Livewire;

use App\Models\BusinessSector;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class BusinessSectorCreateUpdate extends Component
{
    public $idBusinessSector;
    public $name, $description;

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
            BusinessSector::where('id', $this->idBusinessSector)
                ->update(
                    [
                        'name' => $this->name,
                        'description' => $this->description
                    ]);
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
        $businessSector = [
            'name' => $this->name,
            'description' => $this->description
        ];
        try {
            BusinessSector::create($businessSector);

        } catch (\Exception $exception) {
            dd($exception);
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
