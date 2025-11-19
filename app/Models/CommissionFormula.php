<?php

namespace App\Models;

use App\Traits\HasAuditing;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CommissionFormula extends Model
{
    use HasFactory, HasAuditing, SoftDeletes;

    protected $fillable = [
        'initial_commission',
        'final_commission',
        'name',
        'description',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'initial_commission' => 'decimal:2',
        'final_commission' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Get the commission range
     *
     * @return string
     */
    public function getCommissionRange(): string
    {
        return $this->initial_commission . '% - ' . $this->final_commission . '%';
    }

    /**
     * Calculate commission based on a value
     *
     * @param float $value
     * @param string $type 'initial' or 'final'
     * @return float
     */
    public function calculateCommission(float $value, string $type = 'initial'): float
    {
        $rate = $type === 'final' ? $this->final_commission : $this->initial_commission;
        return ($value * $rate) / 100;
    }

    /**
     * Scope to get only active formulas
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get formulas within a range
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param float $min
     * @param float $max
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithinRange($query, float $min, float $max)
    {
        return $query->where('initial_commission', '>=', $min)
                     ->where('final_commission', '<=', $max);
    }
}
