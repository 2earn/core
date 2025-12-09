<?php

namespace App\Models;

use App\Traits\HasAuditing;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PlanLabel extends Model
{
    use HasFactory, HasAuditing, SoftDeletes;

    protected $table = 'plan_labels';

    const IMAGE_TYPE_ICON = 'icon';
    const DEFAULT_IMAGE_TYPE_ICON = 'resources/images/plan-labels/icon/default-plan-label-icon.png';

    protected $fillable = [
        'step',
        'rate',
        'stars',
        'initial_commission',
        'final_commission',
        'name',
        'description',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'step' => 'integer',
        'rate' => 'float',
        'stars' => 'integer',
        'initial_commission' => 'decimal:2',
        'final_commission' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function getCommissionRange(): string
    {
        return $this->initial_commission . '% - ' . $this->final_commission . '%';
    }

    public function iconImage()
    {
        return $this->morphOne(Image::class, 'imageable')->where('type', '=', self::IMAGE_TYPE_ICON);
    }

    public function deals()
    {
        return $this->hasMany(Deal::class, 'plan', 'id');
    }

    public function calculateCommission(float $value, string $type = 'initial'): float
    {
        $rate = $type === 'final' ? $this->final_commission : $this->initial_commission;
        return ($value * $rate) / 100;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeWithinRange($query, float $min, float $max)
    {
        return $query->where('initial_commission', '>=', $min)
                     ->where('final_commission', '<=', $max);
    }

    public function scopeByStars($query, int $stars)
    {
        return $query->where('stars', $stars);
    }
}

