<?php

namespace App\Http\Livewire;

use App\Models\Deal;
use Illuminate\Support\Facades\Lang;
use Livewire\Component;

class DealsIndex extends Component
{
    const INDEX_ROUTE_NAME = 'deals_index';

    public $listeners = ['delete' => 'delete'];

    public function delete($id)
    {
        try {
            Deal::findOrFail($id)->delete();

            return redirect()->route(self::INDEX_ROUTE_NAME, ['locale' => app()->getLocale()])->with('success', Lang::get('Deal Deleted Successfully!!'));
        } catch (\Exception $exception) {
            return redirect()->route(self::INDEX_ROUTE_NAME, ['locale' => app()->getLocale()])->with('warning', Lang::get('This Deal cant be Deleted !') . " " . $exception->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.deals-index')->extends('layouts.master')->section('content');
    }
}
