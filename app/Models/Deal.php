<?php

namespace App\Models;

use Core\Models\Platform;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Deal extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'validated',
        'status',
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

        'created_by_id',
        'platform_id',
    ];


    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function productDealHistory(): HasMany
    {
        return $this->hasMany(ProductDealHistory::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function platform()
    {
        return $this->hasOne(Platform::class, 'id', 'platform_id');
    }


    public function getIndexOfcurrentTurnover($currentTurnover)
    {
        return $currentTurnover / $this->precision;
    }


    public function getProviderUnitTurnoverOutDeal(): float
    {
        return $this->precision * $this->provider_turnover / $this->objective_turnover;
    }


    public function getCommissionProgressiveStepDuringTheDealExecution(): float
    {
        return (2 * pow($this->precision, 2) * ($this->final_commission - $this->initial_commission)) / ($this->objective_turnover - $this->precision);
    }


    public function getProviderTotalNetTurnover($currentTurnover): float
    {
        return $this->getIndexOfcurrentTurnover($currentTurnover) * ($this->precision - $this->initial_commission * $this->precision) - $this->getCommissionProgressiveStepDuringTheDealExecution() * (($this->getIndexOfcurrentTurnover($currentTurnover) * ($this->getIndexOfcurrentTurnover($currentTurnover) - 1)) / 2);
    }


    public function getProviderTotalTurnoverOutOfDeal($currentTurnover): float
    {
        return $this->getProviderUnitTurnoverOutDeal() * $this->getIndexOfcurrentTurnover($currentTurnover);
    }

    public function getProviderUnitNetTurnover($currentTurnover): float
    {
        return $this->precision - $this->getProviderTotalNetTurnover($currentTurnover) * ($this->getIndexOfcurrentTurnover($currentTurnover) - 1) - $this->initial_commission * $this->precision;
    }


    public function getCurrentTotal2earnCashMargin($currentTurnover): float
    {
        return $currentTurnover - $this->getProviderTotalTurnoverOutOfDeal($currentTurnover);
    }

    public function getCurrent2earnCashMarginComparedToCurrentTurnover($currentTurnover): float
    {

        return $this->getCurrentTotal2earnCashMargin($currentTurnover) / $currentTurnover;
    }

    public function getProviderTurnoverDifference($currentTurnover): float
    {
        return $this->getProviderTotalNetTurnover($currentTurnover) - $this->getProviderTotalTurnoverOutOfDeal($currentTurnover);
    }

    public function getProviderTurnoverSum($currentTurnover): float
    {

        return $this->getProviderTotalTurnoverOutOfDeal($currentTurnover) + $this->getProviderTotalTurnoverOutOfDeal($currentTurnover);
    }

    public function getProviderTotalProfit($currentTurnover): float
    {
        return $this->getProviderTotalNetTurnover($currentTurnover) * $this->items_profit_average;
    }

    public function getProviderTotalProfitOutOfDeal($currentTurnover): float
    {
        return $this->getProviderTotalTurnoverOutOfDeal($currentTurnover) * $this->items_profit_average;
    }


    public function getCurrentCashBackMargin($currentTurnover): float
    {
        return $this->getCurrentTotal2earnCashMargin($currentTurnover) * $this->cash_back_margin_percentage;
    }


    public function getCurrentFranchisorMargin($franchisorMarginPercentage, $currentTurnover): float
    {
        return $this->getCurrentTotal2earnCashMargin($currentTurnover) * $franchisorMarginPercentage;
    }


    public function getCurrentInfluencerMargin($influencerMarginPercentage, $currentTurnover): float
    {
        return $this->getCurrentTotal2earnCashMargin($currentTurnover) * $influencerMarginPercentage;
    }

    public function getCurrentProactiveConsumptionMargin($currentTurnover): float
    {
        return $this->getCurrentTotal2earnCashMargin($currentTurnover) * $this->proactive_consumption_margin_percentage;
    }

    public function getCurrentPrescriptorMargin($prescriptorMarginPercentage, $currentTurnover): float
    {
        return $this->getCurrentTotal2earnCashMargin($currentTurnover) * $prescriptorMarginPercentage;
    }


    public function getCurrentSupporterMargin($supporterMarginPercentage,$currentTurnover): float
    {
        return $this->getCurrentTotal2earnCashMargin($currentTurnover) * $supporterMarginPercentage;
    }

    public function getCurrent2earnCashNetMargin($CashMarginPercentage,$currentTurnover): float
    {
        return $this->getCurrentTotal2earnCashMargin($currentTurnover) * $CashMarginPercentage;
    }

    public function getProviderProfitDifference($currentTurnover): float
    {

        return $this->getProviderTotalProfit($currentTurnover) - $this->getProviderTotalProfitOutOfDeal($currentTurnover);
    }

    public function getProviderProfitSum($currentTurnover): float
    {
        return $this->getProviderTotalProfit($currentTurnover) + $this->getProviderTotalProfitOutOfDeal($currentTurnover);
    }

}
