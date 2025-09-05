<?php

namespace App\Livewire;

use Livewire\Component;

class OperationCategoryIndex extends Component
{
    public function render()
    {
        return view('livewire.operation-category-index')->extends('layouts.master')->section('content');
    }
}
