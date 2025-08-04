<?php

namespace App\Livewire;

use App\Models\OperationCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class OperationCategoryCreateUpdate extends Component
{
    public $idCategory;
    public $name;
    public $update = false;

    protected $rules = ['name' => 'required'];

    public function mount(Request $request)
    {
        $this->idCategory = $request->input('idCategory');
        if (!is_null( $this->idCategory)) {
            $this->edit( $this->idCategory);
        }
    }

    public function edit($idCategory)
    {
        $category = OperationCategory::find($idCategory);
        $this->idCategory = $idCategory;
        $this->name = $category->name;
        $this->update = true;
    }

    public function cancel()
    {
        return redirect()->route('balances_categories_index', ['locale' => app()->getLocale(), 'idCategory' => $this->idCategory])->with('warning', Lang::get('Operation category operation cancelled'));
    }

    public function updateCategory()
    {
        $this->validate();
        try {
            OperationCategory::where('id', $this->idCategory)
                ->update([
                    'name' => $this->name
                ]);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->route('balances_categories_index', ['locale' => app()->getLocale()])->with('danger', Lang::get('Something goes wrong while updating Operation category '));
        }
        return redirect()->route('balances_categories_index', ['locale' => app()->getLocale()])->with('success', Lang::get('Operation category Updated Successfully'));
    }

    public function storeCategory()
    {
        $this->validate();
        try {
            OperationCategory::create(['name' => $this->name]);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->route('balances_categories_index', ['locale' => app()->getLocale()])->with('danger', Lang::get('Something goes wrong while creating Operation category'));
        }
        return redirect()->route('balances_categories_index', ['locale' => app()->getLocale()])->with('success', Lang::get('Operation category Created Successfully'));
    }

    public function render()
    {
        $params = ['operationCategory' => OperationCategory::find($this->idCategory)];
        return view('livewire.operation-category-create-update', $params)->extends('layouts.master')->section('content');
    }
}
