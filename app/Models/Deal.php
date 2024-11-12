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
        'out_provider_turnover',
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
        'current_turnover',
        'item_price',
        'current_turnover_index',
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

    /**
     * Calculate index of current turnover.
     */
    public function getIndexOfCurrentTurnover()
    {
        return $this->currentTurnover / $this->precision;
    }

    /**
     * Calculate provider unit turnover out of deal.
     */
    public function getProviderUnitTurnoverOutDeal(): float
    {
        return $this->precision * $this->providerTurnover / $this->objectiveTurnover;
    }

    /**
     * Calculate commission progressive step during the deal execution.
     */
    public function getCommissionProgressiveStepDuringTheDealExecution(): float
    {
        return (2 * $this->precision * $this->precision * ($this->finalCommission - $this->initialCommission)) / ($this->objectiveTurnover - $this->precision);
    }

    /**
     * Calculate provider total net turnover.
     */
    public function getProviderTotalNetTurnover($precision, $initialCommission, $finalCommission, $objectiveTurnover, $currentTurnover): float
    {
        $vstep = $this->getCommissionProgressiveStepDuringTheDealExecution();
        $vidx = $this->getIndexOfCurrentTurnover();
        return $vidx * ($this->precision - $this->initialCommission * $this->precision) - $vstep * (($vidx * ($vidx - 1)) / 2);
    }

    /**
     * Calculate provider total turnover out of deal.
     */
    public function getProviderTotalTurnoverOutOfDeal($precision, $objectiveTurnover, $providerTurnover, $currentTurnover): float
    {
        return $this->getProviderUnitTurnoverOutDeal() * $this->getIndexOfCurrentTurnover();
    }

    /**
     * Calculate provider unit net turnover.
     *
     */
    public function getProviderUnitNetTurnover(): float
    {
        $vstep = $this->getProviderTotalNetTurnover();
        $vidx = self::idx();
        return $this->precision - $vstep * ($vidx - 1) - $this->initialCommission * $this->precision;
    }

    /**
     * Calculate current total 2earn.cash margin.
     */
    public  function getCurrentTotal2earnCashMargin(): float
    {
        $vptt = self::getProviderTotalTurnoverOutOfDeal();
        return $this->currentTurnover - $vptt;
    }

    /**
     * Calculate current 2earn.cash margin compared to current turnover.
     */
    public function getCurrent2earnCashMarginComparedToCurrentTurnover(): float
    {
        $vmttec = $this->getCurrentTotal2earnCashMargin();
        return $vmttec / $this->currentTurnover;
    }

    /**
     * Calculate provider turnover difference.
     */
    public  function getProviderTurnoverDifference(): float
    {
        $vpttod = $this->getProviderTotalTurnoverOutOfDeal();
        $vptt = $this->getProviderTotalNetTurnover();
        return $vptt - $vpttod;
    }

    /**
     * Calculate provider turnover sum.
     */
    public  function getProviderTurnoverSum(): float
    {
        $vpttod = $this->getProviderTotalTurnoverOutOfDeal();
        $vptt = $this->getProviderTotalTurnoverOutOfDeal();
        return $vptt + $vpttod;
    }

    /**
     * Calculate provider total profit.
     */
    public function getProviderTotalProfit($profitAverage): float
    {
        $vptt = $this->getProviderTotalNetTurnover();
        return $vptt * $profitAverage;
    }

    /**
     * Calculate provider total profit out of deal.
     */
    public function getPproviderTotalProfitOutOfDeal($profitAverage): float
    {
        $vpttod = $this->getProviderTotalTurnoverOutOfDeal();
        return $vpttod * $profitAverage;
    }

    /**
     * Calculate current cash back margin.
     */
    public function getCurrentCashBackMargin(): float
    {
        $vmttec = $this->getCurrentTotal2earnCashMargin();
        return $vmttec * $this->cashBackMarginPercentage;
    }

    /**
     * Calculate current franchisor margin.
     */
    public function getCurrentFranchisorMargin($franchisorMarginPercentage): float
    {
        $vmttec = $this->getCurrentTotal2earnCashMargin();
        return $vmttec * $franchisorMarginPercentage;
    }

    /**
     * Calculate current influencer margin.
     */
    public function getCurrentInfluencerMargin($influencerMarginPercentage): float
    {
        $vmttec = $this->getCurrentTotal2earnCashMargin();
        return $vmttec * $influencerMarginPercentage;
    }

    /**
     * Calculate current proactive consumption margin.
     */
    public function getCurrentProactiveConsumptionMargin(): float
    {
        $vmttec = $this->getCurrentTotal2earnCashMargin();
        return $vmttec * $this->proactive_consumption_margin_percentage;
    }

    /**
     * Calculate current prescriptor margin.
     */
    public function getCurrentPrescriptorMargin($prescriptorMarginPercentage): float
    {
        $vmttec = $this->getCurrentTotal2earnCashMargin();
        return $vmttec * $prescriptorMarginPercentage;
    }

    /**
     * Calculate current supporter margin.
     */
    public function getCurrentSupporterMargin($supporterMarginPercentage): float
    {
        $vmttec = $this->getCurrentTotal2earnCashMargin();
        return $vmttec * $supporterMarginPercentage;
    }

    /**
     * Calculate current 2earn.cash net margin.
     */
    public function getCurrent2earnCashNetMargin($mtecPercentage): float
    {
        $vmttec = $this->getCurrentTotal2earnCashMargin();
        return $vmttec * $mtecPercentage;
    }

    /**
     * Calculate provider profit difference.
     */
    public function getProviderProfitDifference(): float
    {
        $vptpod = $this->getPproviderTotalProfitOutOfDeal();
        $vptp = $this->getProviderTotalProfit();
        return $vptp - $vptpod;
    }

    /**
     * Calculate provider profit sum.

     */
    public function getProviderProfitSum(): float
    {
        $vptpod = $this->getPproviderTotalProfitOutOfDeal();
        $vptp = $this->getProviderTotalProfit();
        return $vptp + $vptpod;
    }

}
