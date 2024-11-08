<?php

namespace App\Models;

use Core\Enum\DealStatus;
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


}
