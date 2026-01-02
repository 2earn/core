<?php

namespace App\Models;

use App\Traits\HasAuditing;
use App\Enums\OrderEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use InvalidArgumentException;

class Order extends Model
{
    use HasFactory, HasAuditing;

    protected $fillable = [
        'out_of_deal_amount',
        'deal_amount_before_discount',
        'total_order',
        'total_order_quantity',
        'deal_amount_after_discounts',
        'amount_after_discount',
        'paid_cash',
        'commission_2_earn',
        'deal_amount_for_partner',
        'commission_for_camembert',
        'total_final_discount',
        'total_final_discount_percentage',
        'total_lost_discount',
        'total_lost_discount_percentage',
        'user_id',
        'platform_id',
        'note',
        'status',
        'simulation_datetime',
        'simulation_result',
        'simulation_details',
        'payment_datetime',
        'payment_result',
        'payment_details',
        'created_by',
        'updated_by',
    ];
    protected $casts = [
        'status' => OrderEnum::class,
        'simulation_result' => 'boolean',
        'payment_result' => 'boolean',
    ];

    public function OrderDetails(): HasMany
    {
        return $this->hasMany(OrderDetail::class, 'order_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function platform()
    {
        return $this->belongsTo(\Core\Models\Platform::class, 'platform_id', 'id');
    }

    public function updateStatus(OrderEnum $newStatus)
    {
        if (!OrderEnum::tryFrom($newStatus->value)) {
            throw new InvalidArgumentException("Invalid status provided.");
        }
        $this->status = $newStatus;
        $this->save();
        return $this->status;
    }

}
