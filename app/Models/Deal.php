<?php

namespace App\Models;

use App\Traits\HasAuditing;
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
    use HasFactory, HasAuditing;

    protected $fillable = [
        'name',
        'description',
        'validated',
        'type',
        'status',
        'current_turnover',
        'target_turnover',
        'is_turnover',
        'discount',
        'start_date',
        'end_date',
        'initial_commission',
        'final_commission',
        'plan',
        'earn_profit',
        'jackpot',
        'tree_remuneration',
        'proactive_cashback',
        'total_commission_value',
        'total_unused_cashback_value',
        'created_by_id',
        'platform_id',
        'cash_company_profit',
        'cash_jackpot',
        'cash_tree',
        'cash_cashback',
        'created_by',
        'updated_by',
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

    public function commissionPlan()
    {
        return $this->belongsTo(PlanLabel::class, 'plan', 'id');
    }

    public function cashBalance(): HasMany
    {
        return $this->hasMany(CashBalances::class);
    }

    public function validationRequests(): HasMany
    {
        return $this->hasMany(DealValidationRequest::class);
    }

    public function changeRequests(): HasMany
    {
        return $this->hasMany(DealChangeRequest::class);
    }

    public function pendingChangeRequest()
    {
        return $this->hasOne(DealChangeRequest::class)->where('status', 'pending')->latest();
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

    public static function getCommissionPercentage($deal, $newTurnOver = null)
    {
        if (is_null($newTurnOver)) {
            $newTurnOver = $deal->current_turnover;
        }
        if ($newTurnOver > $deal->target_turnover) {
            return $deal->final_commission;
        }
        return max(0, min(100, (($deal->final_commission - $deal->initial_commission) / $deal->target_turnover) * $newTurnOver + $deal->initial_commission));
    }

    public function updateTurnover($pushase)
    {
        $this->current_turnover = $this->current_turnover + $pushase;
        $this->save();
        return $this->current_turnover;
    }

    public static function getCamombertPercentage($deal)
    {
        $commission = Deal::getCommissionPercentage($deal);
        return $commission * $deal->current_turnover / 100;
    }

    public static function getCamombertPartPercentage($deal, $percentage)
    {
        $commission = Deal::getCommissionPercentage($deal);
        return ($commission * $deal->current_turnover / 100) / 100 * $percentage;
    }

    /**
     * Boot method to handle automatic plan determination
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($deal) {
            if (!$deal->plan && $deal->initial_commission !== null && $deal->final_commission !== null) {
                $deal->plan = self::determinePlan($deal->initial_commission, $deal->final_commission);
            }
        });

        static::updating(function ($deal) {
            if ($deal->isDirty(['initial_commission', 'final_commission'])) {
                $deal->plan = self::determinePlan($deal->initial_commission, $deal->final_commission);
            }
        });
    }

    /**
     * Determine the commission plan based on initial and final commission values
     *
     * @param float $initialCommission
     * @param float $finalCommission
     * @return int|null
     */
    public static function determinePlan($initialCommission, $finalCommission)
    {
        $formula = PlanLabel::where('is_active', true)
            ->where('initial_commission', '<=', $initialCommission)
            ->where('final_commission', '>=', $finalCommission)
            ->orderBy('initial_commission', 'desc')
            ->orderBy('final_commission', 'asc')
            ->first();

        return $formula ? $formula->id : null;
    }

    /**
     * Get the plan name
     *
     * @return string|null
     */
    public function getPlanNameAttribute()
    {
        return $this->commissionPlan ? $this->commissionPlan->name : null;
    }

    /**
     * Get the commission range from the plan
     *
     * @return string|null
     */
    public function getPlanCommissionRangeAttribute()
    {
        return $this->commissionPlan ? $this->commissionPlan->getCommissionRange() : null;
    }

}
