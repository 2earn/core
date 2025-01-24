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
        'target_turnover',
        'is_turnover',
        'discount',
        'start_date',
        'end_date',
        'initial_commission',
        'final_commission',
        'earn_profit',
        'jackpot',
        'tree_remuneration',
        'proactive_cashback',
        'min_percentage_cashback',
        'max_percentage_cashback',
        'total_commission_value',
        'total_unused_cashback_value',
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

    public static function getCommissionPercentage($deal, $newTurnOver)
    {
        return (($deal->final_commission - $deal->initial_commission) / $deal->target_turnover) * $newTurnOver + $deal->initial_commission - $deal->discount;
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
