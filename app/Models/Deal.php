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
        'status',
        'objective_turnover',
        'start_date',
        'end_date',
        'start_date',
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
        'created_by',
    ];


    public function createdBy()
    {
        return $this->belongsTo(User::class);
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
        return $this->hasOne(Platform::class);
    }


}
