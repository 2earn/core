<?php

namespace App\Http\Livewire;

use App\Models\Deal;
use Core\Enum\DealStatus;
use Core\Models\Platform;
use Illuminate\Support\Facades\Lang;
use Livewire\Component;

class DealsIndex extends Component
{
    const INDEX_ROUTE_NAME = 'deals_index';

    public $listeners = [
        'delete' => 'delete',
        'updateDeal' => 'updateDeal',
    ];
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

    public function updateDeal($id, $status)
    {
        match (intval($status)) {
            0 => $this->validateDeal($id),
            2 => $this->open($id),
            3 => $this->close($id),
            4 => $this->archive($id),
        };
    }

    public function validateDeal($id)
    {
        try {
            $deal = Deal::findOrFail($id);
            $deal->validated = true;
            $deal->save();
            return redirect()->route(self::INDEX_ROUTE_NAME, ['locale' => app()->getLocale()])->with('success', Lang::get('Deal Validated Successfully!!'));
        } catch (\Exception $exception) {
            return redirect()->route(self::INDEX_ROUTE_NAME, ['locale' => app()->getLocale()])->with('warning', Lang::get('This Deal cant be Validated !') . " " . $exception->getMessage());
        }
    }

    public function open($id)
    {
        try {
            $deal = Deal::findOrFail($id);
            $deal->status = DealStatus::Opened->value;
            $deal->save();
            return redirect()->route(self::INDEX_ROUTE_NAME, ['locale' => app()->getLocale()])->with('success', Lang::get('Deal Opened Successfully!!'));
        } catch (\Exception $exception) {
            return redirect()->route(self::INDEX_ROUTE_NAME, ['locale' => app()->getLocale()])->with('warning', Lang::get('This Deal cant be Opened !') . " " . $exception->getMessage());
        }
    }

    public function close($id)
    {
        try {
            $deal = Deal::findOrFail($id);
            $deal->status = DealStatus::Closed->value;
            $deal->save();
            return redirect()->route(self::INDEX_ROUTE_NAME, ['locale' => app()->getLocale()])->with('success', Lang::get('Deal Closed Successfully!!'));
        } catch (\Exception $exception) {
            return redirect()->route(self::INDEX_ROUTE_NAME, ['locale' => app()->getLocale()])->with('warning', Lang::get('This Deal cant be Closed !') . " " . $exception->getMessage());
        }
    }

    public function archive($id)
    {
        try {
            $deal = Deal::findOrFail($id);
            $deal->status = DealStatus::Archived->value;
            $deal->save();
            return redirect()->route(self::INDEX_ROUTE_NAME, ['locale' => app()->getLocale()])->with('success', Lang::get('Deal Archived Successfully!!'));
        } catch (\Exception $exception) {
            return redirect()->route(self::INDEX_ROUTE_NAME, ['locale' => app()->getLocale()])->with('warning', Lang::get('This Deal cant be Archived !') . " " . $exception->getMessage());
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
