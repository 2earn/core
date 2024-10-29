<?php

namespace App\Http\Livewire;

use Core\Enum\PlatformType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Route;
use Livewire\Component;

class PlatformCreateUpdate extends Component
{
    public
        $idPlatform,
        $name,
        $description,
        $link,
        $type;
    public $enabled = false;

    protected $rules = ['name' => 'required', 'description' => 'required'];
    public $update = false;
    public $types = [];

    public function mount($id, Request $request)
    {
        $this->idPlatform = Route::current()->parameter('id');
        if (!is_null($this->idPlatform)) {
            $this->edit($this->idPlatform);
        }
        $this->types = [
            ['name' => PlatformType::Main->name, 'value' => PlatformType::Main->value,],
            ['name' => PlatformType::Child->name, 'value' => PlatformType::Child->value,],
            ['name' => PlatformType::Partner->name, 'value' => PlatformType::Partner->value,]
        ];
    }

    public function cancel()
    {
        return redirect()->route('platform_index', ['locale' => app()->getLocale(), 'idTarget' => $this->idTarget])->with('warning', Lang::get('Platform operation cancelled!!'));
    }

    public function edit($idPlatform)
    {
        $platform = Platform::findOrFail($idPlatform);
        $this->name = $platform->name;
        $this->description = $platform->description;
        $this->idPlatform = $platform->id;
        $this->type = $platform->type;
        $this->enabled = $platform->enabled;
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
                'type' => $this->type,
                'link' => $this->link
            ];
            \Core\Models\Platform::where('id', $this->idPlatform)
                ->update($params);
        } catch (\Exception $exception) {
            return redirect()->route('platform_index', ['locale' => app()->getLocale()])->with('danger', Lang::get('Something goes wrong while updating Platform!!'));
        }
        return redirect()->route('platform_index', ['locale' => app()->getLocale()])->with('success', Lang::get('Platform Updated Successfully!!'));

    }

    public function store()
    {
        try {
            $this->validate();
            $params = [
                'name' => $this->name,
                'description' => $this->description,
                'enabled' => $this->enabled,
                'type' => $this->type,
                'link' => $this->link
            ];
            $platform = \Core\Models\Platform::create($params);
        } catch (\Exception $exception) {
            return redirect()->route('platform_create_update', ['locale' => app()->getLocale()])->with('danger', Lang::get('Something goes wrong while creating Platform!!'). ' ' .$exception->getMessage());
        }
        return redirect()->route('platform_index', ['locale' => app()->getLocale()])->with('success', Lang::get('Platform Created Successfully!!') . ' ' . $platform->name);
    }


    public function render()
    {
        return view('livewire.platform-create-update')->extends('layouts.master')->section('content');
    }
}
