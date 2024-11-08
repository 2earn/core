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
     *
     * @param int $precision
     * @param int $currentTurnover
     * @return float
     */
    public static function idx($precision, $currentTurnover)
    {
        return $currentTurnover / $precision;
    }

    /**
     * Calculate provider unit turnover out of deal.
     *
     * @param float $precision
     * @param float $objectiveTurnover
     * @param float $providerTurnover
     * @return float
     */
    public static function putod($precision, $objectiveTurnover, $providerTurnover)
    {
        return $precision * $providerTurnover / $objectiveTurnover;
    }

    /**
     * Calculate commission progressive step during the deal execution.
     *
     * @param float $precision
     * @param float $initialCommission
     * @param float $finalCommission
     * @param float $objectiveTurnover
     * @return float
     */
    public static function step($precision, $initialCommission, $finalCommission, $objectiveTurnover)
    {
        return (2 * $precision * $precision * ($finalCommission - $initialCommission)) / ($objectiveTurnover - $precision);
    }

    /**
     * Calculate provider total net turnover.
     *
     * @param float $precision
     * @param float $initialCommission
     * @param float $finalCommission
     * @param float $objectiveTurnover
     * @param float $currentTurnover
     * @return float
     */
    public static function ptt($precision, $initialCommission, $finalCommission, $objectiveTurnover, $currentTurnover)
    {
        $vstep = self::step($precision, $finalCommission, $initialCommission, $objectiveTurnover);
        $vidx = self::idx($precision, $currentTurnover);
        return $vidx * ($precision - $initialCommission * $precision) - $vstep * (($vidx * ($vidx - 1)) / 2);
    }

    /**
     * Calculate provider total turnover out of deal.
     *
     * @param float $precision
     * @param float $objectiveTurnover
     * @param float $providerTurnover
     * @param float $currentTurnover
     * @return float
     */
    public static function pttod($precision, $objectiveTurnover, $providerTurnover, $currentTurnover)
    {
        return self::putod($precision, $objectiveTurnover, $providerTurnover) * self::idx($precision, $currentTurnover);
    }

    /**
     * Calculate provider unit net turnover.
     *
     * @param float $precision
     * @param float $initialCommission
     * @param float $finalCommission
     * @param float $objectiveTurnover
     * @param float $currentTurnover
     * @return float
     */
    public static function put($precision, $initialCommission, $finalCommission, $objectiveTurnover, $currentTurnover)
    {
        $vstep = self::step($precision, $finalCommission, $initialCommission, $objectiveTurnover);
        $vidx = self::idx($precision, $currentTurnover);
        return $precision - $vstep * ($vidx - 1) - $initialCommission * $precision;
    }

    /**
     * Calculate current total 2earn.cash margin.
     *
     * @param float $precision
     * @param float $initialCommission
     * @param float $finalCommission
     * @param float $objectiveTurnover
     * @param float $currentTurnover
     * @return float
     */
    public static function mttec($precision, $initialCommission, $finalCommission, $objectiveTurnover, $currentTurnover)
    {
        $vptt = self::ptt($precision, $initialCommission, $finalCommission, $objectiveTurnover, $currentTurnover);
        return $currentTurnover - $vptt;
    }

    /**
     * Calculate current 2earn.cash margin compared to current turnover.
     *
     * @param float $precision
     * @param float $initialCommission
     * @param float $finalCommission
     * @param float $objectiveTurnover
     * @param float $currentTurnover
     * @return float
     */
    public static function mttecprc($precision, $initialCommission, $finalCommission, $objectiveTurnover, $currentTurnover)
    {
        $vmttec = self::mttec($precision, $initialCommission, $finalCommission, $objectiveTurnover, $currentTurnover);
        return $vmttec / $currentTurnover;
    }

    /**
     * Calculate provider turnover difference.
     *
     * @param float $precision
     * @param float $objectiveTurnover
     * @param float $providerTurnover
     * @param float $currentTurnover
     * @param float $finalCommission
     * @param float $initialCommission
     * @return float
     */
    public static function pdt($precision, $objectiveTurnover, $providerTurnover, $currentTurnover, $finalCommission, $initialCommission)
    {
        $vpttod = self::pttod($precision, $objectiveTurnover, $providerTurnover, $currentTurnover);
        $vptt = self::ptt($precision, $initialCommission, $finalCommission, $objectiveTurnover, $currentTurnover);
        return $vptt - $vpttod;
    }

    /**
     * Calculate provider turnover sum.
     *
     * @param float $precision
     * @param float $objectiveTurnover
     * @param float $providerTurnover
     * @param float $currentTurnover
     * @param float $finalCommission
     * @param float $initialCommission
     * @return float
     */
    public static function pst($precision, $objectiveTurnover, $providerTurnover, $currentTurnover, $finalCommission, $initialCommission)
    {
        $vpttod = self::pttod($precision, $objectiveTurnover, $providerTurnover, $currentTurnover);
        $vptt = self::ptt($precision, $initialCommission, $finalCommission, $objectiveTurnover, $currentTurnover);
        return $vptt + $vpttod;
    }

    /**
     * Calculate provider total profit.
     *
     * @param float $precision
     * @param float $initialCommission
     * @param float $finalCommission
     * @param float $objectiveTurnover
     * @param float $currentTurnover
     * @param float $profitAverage
     * @return float
     */
    public static function ptp($precision, $initialCommission, $finalCommission, $objectiveTurnover, $currentTurnover, $profitAverage)
    {
        $vptt = self::ptt($precision, $initialCommission, $finalCommission, $objectiveTurnover, $currentTurnover);
        return $vptt * $profitAverage;
    }

    /**
     * Calculate provider total profit out of deal.
     *
     * @param float $precision
     * @param float $objectiveTurnover
     * @param float $providerTurnover
     * @param float $currentTurnover
     * @param float $profitAverage
     * @return float
     */
    public static function ptpod($precision, $objectiveTurnover, $providerTurnover, $currentTurnover, $profitAverage)
    {
        $vpttod = self::pttod($precision, $objectiveTurnover, $providerTurnover, $currentTurnover);
        return $vpttod * $profitAverage;
    }

    /**
     * Calculate current cash back margin.
     *
     * @param float $precision
     * @param float $initialCommission
     * @param float $finalCommission
     * @param float $objectiveTurnover
     * @param float $currentTurnover
     * @param float $cashBackMarginPercentage
     * @return float
     */
    public static function mcb($precision, $initialCommission, $finalCommission, $objectiveTurnover, $currentTurnover, $cashBackMarginPercentage)
    {
        $vmttec = self::mttec($precision, $initialCommission, $finalCommission, $objectiveTurnover, $currentTurnover);
        return $vmttec * $cashBackMarginPercentage;
    }

    /**
     * Calculate current franchisor margin.
     *
     * @param float $precision
     * @param float $initialCommission
     * @param float $finalCommission
     * @param float $objectiveTurnover
     * @param float $currentTurnover
     * @param float $franchisorMarginPercentage
     * @return float
     */
    public static function mfran($precision, $initialCommission, $finalCommission, $objectiveTurnover, $currentTurnover, $franchisorMarginPercentage)
    {
        $vmttec = self::mttec($precision, $initialCommission, $finalCommission, $objectiveTurnover, $currentTurnover);
        return $vmttec * $franchisorMarginPercentage;
    }

    /**
     * Calculate current influencer margin.
     *
     * @param float $precision
     * @param float $initialCommission
     * @param float $finalCommission
     * @param float $objectiveTurnover
     * @param float $currentTurnover
     * @param float $influencerMarginPercentage
     * @return float
     */
    public static function minf($precision, $initialCommission, $finalCommission, $objectiveTurnover, $currentTurnover, $influencerMarginPercentage)
    {
        $vmttec = self::mttec($precision, $initialCommission, $finalCommission, $objectiveTurnover, $currentTurnover);
        return $vmttec * $influencerMarginPercentage;
    }

    /**
     * Calculate current proactive consumption margin.
     *
     * @param float $precision
     * @param float $initialCommission
     * @param float $finalCommission
     * @param float $objectiveTurnover
     * @param float $currentTurnover
     * @param float $proactiveConsumptionMarginPercentage
     * @return float
     */
    public static function mpc($precision, $initialCommission, $finalCommission, $objectiveTurnover, $currentTurnover, $proactiveConsumptionMarginPercentage)
    {
        $vmttec = self::mttec($precision, $initialCommission, $finalCommission, $objectiveTurnover, $currentTurnover);
        return $vmttec * $proactiveConsumptionMarginPercentage;
    }

    /**
     * Calculate current prescriptor margin.
     *
     * @param float $precision
     * @param float $initialCommission
     * @param float $finalCommission
     * @param float $objectiveTurnover
     * @param float $currentTurnover
     * @param float $prescriptorMarginPercentage
     * @return float
     */
    public static function mpres($precision, $initialCommission, $finalCommission, $objectiveTurnover, $currentTurnover, $prescriptorMarginPercentage)
    {
        $vmttec = self::mttec($precision, $initialCommission, $finalCommission, $objectiveTurnover, $currentTurnover);
        return $vmttec * $prescriptorMarginPercentage;
    }

    /**
     * Calculate current supporter margin.
     *
     * @param float $precision
     * @param float $initialCommission
     * @param float $finalCommission
     * @param float $objectiveTurnover
     * @param float $currentTurnover
     * @param float $supporterMarginPercentage
     * @return float
     */
    public static function msup($precision, $initialCommission, $finalCommission, $objectiveTurnover, $currentTurnover, $supporterMarginPercentage)
    {
        $vmttec = self::mttec($precision, $initialCommission, $finalCommission, $objectiveTurnover, $currentTurnover);
        return $vmttec * $supporterMarginPercentage;
    }

    /**
     * Calculate current 2earn.cash net margin.
     *
     * @param float $precision
     * @param float $initialCommission
     * @param float $finalCommission
     * @param float $objectiveTurnover
     * @param float $currentTurnover
     * @param float $mtecPercentage
     * @return float
     */
    public static function mtec($precision, $initialCommission, $finalCommission, $objectiveTurnover, $currentTurnover, $mtecPercentage)
    {
        $vmttec = self::mttec($precision, $initialCommission, $finalCommission, $objectiveTurnover, $currentTurnover);
        return $vmttec * $mtecPercentage;
    }

    /**
     * Calculate provider profit difference.
     *
     * @param float $precision
     * @param float $objectiveTurnover
     * @param float $providerTurnoverOutOfDeal
     * @param float $currentTurnover
     * @param float $finalCommission
     * @param float $initialCommission
     * @param float $profitAverageInItems
     * @return float
     */
    public static function pdp($precision, $objectiveTurnover, $providerTurnoverOutOfDeal, $currentTurnover, $finalCommission, $initialCommission, $profitAverageInItems)
    {
        $vptpod = self::ptpod($precision, $objectiveTurnover, $providerTurnoverOutOfDeal, $currentTurnover, $profitAverageInItems);
        $vptp = self::ptp($precision, $initialCommission, $finalCommission, $objectiveTurnover, $currentTurnover, $profitAverageInItems);
        return $vptp - $vptpod;
    }

    /**
     * Calculate provider profit sum.
     *
     * @param float $precision
     * @param float $objectiveTurnover
     * @param float $providerTurnoverOutOfDeal
     * @param float $currentTurnover
     * @param float $finalCommission
     * @param float $initialCommission
     * @param float $profitAverageInItems
     * @return float
     */
    public static function psp($precision, $objectiveTurnover, $providerTurnoverOutOfDeal, $currentTurnover, $finalCommission, $initialCommission, $profitAverageInItems)
    {
        $vptpod = self::ptpod($precision, $objectiveTurnover, $providerTurnoverOutOfDeal, $currentTurnover, $profitAverageInItems);
        $vptp = self::ptp($precision, $initialCommission, $finalCommission, $objectiveTurnover, $currentTurnover, $profitAverageInItems);
        return $vptp + $vptpod;
    }


}
