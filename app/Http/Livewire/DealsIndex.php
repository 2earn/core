<?php

namespace App\Http\Livewire;

use App\Models\Deal;
use App\Models\User;
use Core\Models\Platform;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Livewire\Component;

class DealsIndex extends Component
{
    const INDEX_ROUTE_NAME = 'deals_index';

    public $listeners = [
        'delete' => 'delete',
        'updateDeal' => 'updateDeal',
    ];
    public $platforms,$currentRouteName;

    public function mount()
    {
        $this->currentRouteName = Route::currentRouteName();
        if (User::isSuperAdmin()) {
            $this->platforms = Platform::all();
        } else {
            $this->platforms = Platform::where(function ($query) {
                $query->where('administrative_manager_id', '=', auth()->user()->id)
                    ->orWhere('financial_manager_id', '=', auth()->user()->id);
            })->get();
        }
    }

    public function updateDeal($id, $status)
    {
        match (intval($status)) {
            0 => Deal::validateDeal($id),
            2 => Deal::open($id),
            3 => Deal::close($id),
            4 => Deal::archive($id),
        };
    }

    public static function delete($id)
    {
        try {
            Deal::findOrFail($id)->delete();
            return redirect()->route(self::INDEX_ROUTE_NAME, ['locale' => app()->getLocale()])->with('success', Lang::get('Deal Deleted Successfully'));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->route(self::INDEX_ROUTE_NAME, ['locale' => app()->getLocale()])->with('warning', Lang::get('This Deal cant be Deleted !') . " " . $exception->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.deals-index')->extends('layouts.master')->section('content');
    }
}
