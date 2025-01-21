<?php

namespace App\Models;

use Core\Enum\DealStatus;
use Core\Models\Platform;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;

class Deal extends Model
{
    const INDEX_ROUTE_NAME = 'deals_index';
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'validated',
        'status',
        'current_turnover',
        'objective_turnover',
        'start_date',
        'end_date',
        'provider_turnover',
        'items_profit_average',
        'initial_commission',
        'final_commission',
        'precision',
        'progressive_commission',
        'margin_percentage',
        'cash_back_margin_percentage',
        'proactive_consumption_margin_percentage',
        'shareholder_benefits_margin_percentage',
        'tree_margin_percentage',
        'discount',
        'created_by_id',
        'platform_id',
    ];


    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function ItemDealHistory(): HasMany
    {
        return $this->hasMany(ItemDealHistory::class);
    }

    public function Items()
    {
        return $this->hasMany(Item::class);
    }

    public function platform()
    {
        return $this->hasOne(Platform::class, 'id', 'platform_id');
    }


    public function cashBalance(): HasMany
    {
        return $this->hasMany(CashBalances::class);
    }


    public static function getCommissionPercentage($newTurnOver)
    {
        return 20.00;
    }
    public static function validateDeal($id)
    {
        try {
            $deal = Deal::findOrFail($id);
            $deal->validated = true;
            $deal->save();
            return redirect()->route(self::INDEX_ROUTE_NAME, ['locale' => app()->getLocale()])->with('success', Lang::get('Deal Validated Successfully'));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->route(self::INDEX_ROUTE_NAME, ['locale' => app()->getLocale()])->with('warning', Lang::get('This Deal cant be Validated !') . " " . $exception->getMessage());
        }
    }

    public static function open($id)
    {
        try {
            $deal = Deal::findOrFail($id);
            $deal->status = DealStatus::Opened->value;
            $deal->save();
            return redirect()->route(self::INDEX_ROUTE_NAME, ['locale' => app()->getLocale()])->with('success', Lang::get('Deal Opened Successfully'));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->route(self::INDEX_ROUTE_NAME, ['locale' => app()->getLocale()])->with('warning', Lang::get('This Deal cant be Opened !') . " " . $exception->getMessage());
        }
    }

    public static function close($id)
    {
        try {
            $deal = Deal::findOrFail($id);
            $deal->status = DealStatus::Closed->value;
            $deal->save();
            return redirect()->route(self::INDEX_ROUTE_NAME, ['locale' => app()->getLocale()])->with('success', Lang::get('Deal Closed Successfully'));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->route(self::INDEX_ROUTE_NAME, ['locale' => app()->getLocale()])->with('warning', Lang::get('This Deal cant be Closed !') . " " . $exception->getMessage());
        }
    }

    public static function archive($id)
    {
        try {
            $deal = Deal::findOrFail($id);
            $deal->status = DealStatus::Archived->value;
            $deal->save();
            return redirect()->route(self::INDEX_ROUTE_NAME, ['locale' => app()->getLocale()])->with('success', Lang::get('Deal Archived Successfully'));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->route(self::INDEX_ROUTE_NAME, ['locale' => app()->getLocale()])->with('warning', Lang::get('This Deal cant be Archived !') . " " . $exception->getMessage());
        }
    }


}
