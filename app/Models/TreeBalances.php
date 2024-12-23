<?php

namespace App\Models;

use App\Http\Livewire\Platform;
use App\Services\Balances\Balances;
use Core\Models\BalanceOperation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TreeBalances extends Model
{
    use HasFactory;

    protected $fillable = [
        'value',
        'description',
        'current_balance',
        'reference',
        'balance_operation_id',
        'description',
        'beneficiary_id_auto',
        'beneficiary_id',
        'operator_id',
    ];

    public function deal()
    {
        return $this->hasOne(Deal::class);
    }

    public function item()
    {
        return $this->hasOne(Item::class);
    }

    public function orderDetail()
    {
        return $this->hasOne(Order::class);
    }

    public function platform()
    {
        return $this->hasOne(Platform::class);
    }

    public function balanceOperation()
    {
        return $this->hasOne(BalanceOperation::class);
    }


    public function operator()
    {
        return $this->belongsTo(User::class, 'operator_id');
    }

    public function beneficiary()
    {
        return $this->belongsTo(User::class, 'beneficiary_id_auto');
    }

    public static function getTreesNumber($treeBalances)
    {
        try {
            if (DB::table('settings')->where("ParameterName", "=", 'TOTAL_TREE')->exists()) {
                return $treeBalances / DB::table('settings')->where("ParameterName", "=", 'TOTAL_TREE')->pluck('IntegerValue')->first();
            }
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return 0;
        }
    }

    public static function addLine($treeBalances, $item_id = null, $deal_id = null, $order_id = null, $platform_id = null, $order_detail_id = null)
    {
        $treeBalances = Balances::addAutomatedFields($treeBalances, $item_id, $deal_id, $order_id, $platform_id, $order_detail_id);
        self::create($treeBalances);
    }
}
