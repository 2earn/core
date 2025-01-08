<?php

namespace App\Http\Livewire;

use Core\Enum\PlatformType;
use Core\Models\Platform;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class PlatformCreateUpdate extends Component
{
    public
        $idPlatform,
        $name,
        $description,
        $type,
        $link;

    public $enabled = false;
    public $show_profile = false;

    protected $rules = [
        'name' => 'required',
        'description' => 'required',
        'type' => 'required',
        'link' => 'required',
    ];
    public $update = false;
    public $types = [];

    public function mount(Request $request)
    {
        $this->idPlatform = $request->query('idPlatform');
        if (!is_null($this->idPlatform)) {
            $this->edit($this->idPlatform);
        }
        $this->types = [
            ['name' => PlatformType::Full->name, 'value' => PlatformType::Full->value,],
            ['name' => PlatformType::Hybrid->name, 'value' => PlatformType::Hybrid->value,],
            ['name' => PlatformType::Paiement->name, 'value' => PlatformType::Paiement->value,]
        ];
        $this->type = PlatformType::Hybrid->value;
    }

    public function cancel()
    {
        return redirect()->route('platform_index', ['locale' => app()->getLocale()])->with('warning', Lang::get('Platform operation cancelled'));
    }

    public function edit($idPlatform)
    {

        $platform = Platform::findOrFail($idPlatform);
        $this->name = $platform?->name;
        $this->description = $platform->description;
        $this->idPlatform = $platform->id;
        $this->type = $platform->type;
        $this->enabled = $platform->enabled;
        $this->show_profile = $platform->show_profile;
        $this->link = $platform->link;
        $this->update = true;
    }

    public function update()
    {
        $this->validate();
        try {
            $params = [
                'name' => $this->name,
                'description' => $this->description,
                'enabled' => $this->enabled,
                'show_profile' => $this->show_profile,
                'type' => $this->type,
                'link' => $this->link
            ];
            \Core\Models\Platform::where('id', $this->idPlatform)
                ->update($params);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->route('platform_index', ['locale' => app()->getLocale()])->with('danger', Lang::get('Something goes wrong while updating Platform'));
        }
        return redirect()->route('platform_index', ['locale' => app()->getLocale()])->with('success', Lang::get('Platform Updated Successfully'));

    }

    public function store()
    {
        try {
            $this->validate();
            $params = [
                'name' => $this->name,
                'description' => $this->description,
                'enabled' => $this->enabled,
                'show_profile' => $this->show_profile,
                'type' => $this->type,
                'link' => $this->link
            ];
            $platform = Platform::create($params);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->route('platform_create_update', ['locale' => app()->getLocale()])->with('danger', Lang::get('Something goes wrong while creating Platform!!') . ' ' . $exception->getMessage());
        }
        return redirect()->route('platform_index', ['locale' => app()->getLocale()])->with('success', Lang::get('Platform Created Successfully!!') . ' ' . $platform->name);
    }


    public function render()
    {
        return view('livewire.platform-create-update')->extends('layouts.master')->section('content');
    }
}
