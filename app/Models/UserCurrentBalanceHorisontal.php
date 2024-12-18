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
        foreach ($this->bfss_balance as $key => $item) {
            if ($type == $item['type']) {
                return $item['value'];
            }
        }
        return 0;
    }

    public function setBfssBalance($type, $amount)
    {

        $BfssBalances = [];
        foreach ($this->bfss_balance as $item) {
            if (is_array($item)) {
                $Bfss['type'] = $item['type'];
                if ($type == $item['type']) {
                    $Bfss['value'] = $item['value'] + $amount;

                } else {
                    $Bfss['value'] = $item['value'];
                }
                $BfssBalances[] = $Bfss;
            }
        }
        $this->bfss_balance = $BfssBalances;
        $this->save();

    }
}
