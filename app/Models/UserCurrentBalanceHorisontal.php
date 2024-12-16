<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class UserCurrentBalanceHorisontal extends Model
{
    protected $fillable = [
        'user_id',
        'user_id_auto',
        'cash_balance',
        'bfss_balance',
        'cash_balance',
        'discount_balance',
        'tree_balance',
        'sms_balance',
        'share_balance',
        'chances_balance',
    ];

    use HasFactory;

    protected $casts = ['bfss_balance' => 'array'];

    public function getBfssBalance($type)
    {
        if (isset($this->bfss_balance[$type])) {
            return floatval($this->bfss_balance[$type]) ?? null;
        }
        return 0;
    }

    public function setBfssBalance($type, $amount)
    {
        try {
            $BfssBalances = $this->bfss_balance;
            $BfssBalances[$type] = $amount;
            $this->bfss_balance = $BfssBalances;
            return $this->save();
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
        }
    }
}
