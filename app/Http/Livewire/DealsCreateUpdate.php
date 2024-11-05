<?php

namespace App\Http\Livewire;

use App\Models\Deal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Livewire\Component;

class DealsCreateUpdate extends Component
{
    const INDEX_ROUTE_NAME = 'deals_index';
    public $idDeal, $name, $description;
    public $update = false;


    protected $rules = [
        'name' => 'required',
    ];

    public function mount(Request $request)
    {
        $this->idDeal = $request->input('id');
        if (!is_null($this->idDeal)) {
            $this->edit($this->idDeal);
        } else {
            $this->init();
        }
    }

    public function init()
    {
    }

    public function edit($idDeal)
    {
        $this->idDeal = $idDeal;
        $deal = Deal::find($idDeal);
        $this->name = $deal->name;
        $this->description = $deal->description;
        $this->update = true;
    }

    public function cancel()
    {
        return redirect()->route(self::INDEX_ROUTE_NAME, ['locale' => app()->getLocale(), 'id' => $this->idDeal])->with('warning', Lang::get('Deal operation cancelled!!'));
    }

    public function update()
    {
        $this->validate();
        $params = [
            'name' => $this->name,
            'description' => $this->description,
            'created_by' => auth()->user()->id
        ];
        try {
            Deal::where('id', $this->idDeal)
                ->update($params);
        } catch (\Exception $exception) {
            $this->cancel();
            return redirect()->route(self::INDEX_ROUTE_NAME, ['locale' => app()->getLocale()])->with('danger', Lang::get('Something goes wrong while updating Deal!!'));
        }
        return redirect()->route(self::INDEX_ROUTE_NAME, ['locale' => app()->getLocale()])->with('success', Lang::get('Deal Updated Successfully!!'));

    }

    public function store()
    {
        $this->validate();
        $params = [
            'name' => $this->name,
            'description' => $this->description,
            'created_by' => auth()->user()->id,
        ];
        try {
            Deal::create($params);
        } catch (\Exception $exception) {
            return redirect()->route(self::INDEX_ROUTE_NAME, ['locale' => app()->getLocale(),])->with('danger', Lang::get('Something goes wrong while creating Deal!!'));
        }
        return redirect()->route(self::INDEX_ROUTE_NAME, ['locale' => app()->getLocale()])->with('success', Lang::get('Deal Created Successfully!!'));
    }

    public function render()
    {
        return view('livewire.deals-create-update')->extends('layouts.master')->section('content');
    }
}
