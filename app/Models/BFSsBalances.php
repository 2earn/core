<?php

namespace App\Models;

use App\Livewire\PlatformIndex;
use App\Services\Balances\Balances;
use App\Models\Platform;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasAuditing;

class BFSsBalances extends Model
{
    use HasFactory, HasAuditing;

    protected $table = 'bfss_balances';

    const BFS_100 = "100.00";
    const BFS_50 = "50.00";

    protected $fillable = [
        'value',
        'description',
        'current_balance',
        'reference',
        'percentage',
        'balance_operation_id',
        'beneficiary_id_auto',
        'beneficiary_id',
        'operator_id',
        'order_id',
        'created_by',
        'updated_by',
    ];

    public function item()
    {
        return $this->hasOne(Item::class);
    }

    public function deal()
    {
        return $this->hasOne(Deal::class);
    }

    public function order()
    {
        return $this->hasOne(Order::class);
    }

    public function orderDetail()
    {
        return $this->hasOne(Order::class);
    }

    public function platform()
    {
        return $this->hasOne(Platform::class);
    }
    public function operator()
    {
        return $this->belongsTo(User::class, 'operator_id');
    }

    public function beneficiary()
    {
        return $this->belongsTo(User::class, 'beneficiary_id_auto');
    }

    public static function addLine($bfssBalances, $item_id = null, $deal_id = null, $order_id = null, $platform_id = null, $order_detail_id = null)
    {
        self::create(Balances::addAutomatedFields($bfssBalances, $item_id, $deal_id, $order_id, $platform_id, $order_detail_id));
    }
}
