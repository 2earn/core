<?php

namespace App\Livewire;

use App\Services\Balances\OperationCategoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class OperationCategoryCreateUpdate extends Component
{
    public $idCategory;
    public $name;
    public $code;
    public $description;
    public $update = false;

    protected $rules = ['name' => 'required'];

    protected $operationCategoryService;

    public function boot(OperationCategoryService $operationCategoryService)
    {
        $this->operationCategoryService = $operationCategoryService;
    }

    public function mount(Request $request)
    {
        $this->idCategory = $request->input('idCategory');
        if (!is_null($this->idCategory)) {
            $this->edit($this->idCategory);
        }
    }

    public function edit($idCategory)
    {
        $category = $this->operationCategoryService->getCategoryById($idCategory);
        $this->idCategory = $idCategory;
        $this->name = $category->name;
        $this->code = $category->code;
        $this->description = $category->description;
        $this->update = true;
    }

    public function cancel()
    {
        return redirect()->route('balances_categories_index', ['locale' => app()->getLocale(), 'idCategory' => $this->idCategory])->with('warning', Lang::get('Operation category operation canceled'));
    }

    public function updateCategory()
    {
        $this->validate();
        try {
            $this->operationCategoryService->updateCategory($this->idCategory, [
                'name' => $this->name,
                'code' => $this->code,
                'description' => $this->description
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
            $this->operationCategoryService->createCategory([
                'name' => $this->name,
                'code' => $this->code,
                'description' => $this->description
            ]);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->route('balances_categories_index', ['locale' => app()->getLocale()])->with('danger', Lang::get('Something goes wrong while creating Operation category'));
        }
        return redirect()->route('balances_categories_index', ['locale' => app()->getLocale()])->with('success', Lang::get('Operation category Created Successfully'));
    }

    public function render()
    {
        $params = ['operationCategory' => $this->operationCategoryService->getCategoryById($this->idCategory)];
        return view('livewire.operation-category-create-update', $params)->extends('layouts.master')->section('content');
    }
}
