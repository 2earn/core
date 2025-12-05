<?php

namespace App\Models;

use App\Traits\HasAuditing;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CommissionFormula extends Model
{
    use HasFactory, HasAuditing, SoftDeletes;

    const IMAGE_TYPE_LOGO = 'logo';
    const DEFAULT_IMAGE_TYPE_LOGO = 'resources/images/commission-formulas/logo/default-commission-formula-logo.png';

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
     * Get the icon image for this commission formula
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function iconImage()
    {
        return $this->morphOne(Image::class, 'imageable')->where('type', '=', self::IMAGE_TYPE_LOGO);
    }

    /**
     * Get all deals using this commission plan
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function deals()
    {
        return $this->hasMany(Deal::class, 'plan', 'id');
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
