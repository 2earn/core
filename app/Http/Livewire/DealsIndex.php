<?php

namespace App\Http\Livewire;

use App\Models\Deal;
use Core\Models\Platform;
use Illuminate\Support\Facades\Lang;
use Livewire\Component;

class DealsIndex extends Component
{
    const INDEX_ROUTE_NAME = 'deals_index';

    public $listeners = ['delete' => 'delete'];
    public $platforms;

    public function mount()
    {
        if (strtoupper(auth()?->user()?->getRoleNames()->first()) == \App\Models\Survey::SUPER_ADMIN_ROLE_NAME) {
            $this->platforms = Platform::all();
        } else {
            $this->platforms = Platform::where(function ($query) {
                $query->where('administrative_manager_id', '=', auth()->user()->id)
                    ->orWhere('financial_manager_id', '=', auth()->user()->id);
            })->get();
        }
    }

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
