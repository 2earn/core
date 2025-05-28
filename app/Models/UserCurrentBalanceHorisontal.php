<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCurrentBalanceHorisontal extends Model
{
    protected $fillable = [
        'user_id',
        'user_id_auto',
        'cash_balance',
        'bfss_balance',
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
        foreach ($this->bfss_balance as $item) {
            if ($type == $item['type']) {
                return $item['value'];
            }
        }

        return 0;
    }

    public function setBfssBalance($type, $amount)
    {
        $bfss_balance = $this->bfss_balance;
        $changed = false;
        foreach ($bfss_balance as &$item) {
            if ($type === $item['type']) {
                $item['value'] = $amount;
                $changed = true;
            } else {
                $item['value'] = $item['value'];
            }

        }
        if (!$changed) {
            $bfss_balance[] = ['type' => $type, 'value' => $amount];
        }
        $this->bfss_balance = $bfss_balance;
        if ($this->isDirty('bfss_balance')) {
            $this->save();
        }
    }

    public function getChancesBalance($type)
    {
        if (is_array($this->chances_balance)) {
            foreach ($this->chances_balance as $item) {
                if ($type == $item['pool_id']) {
                    return $item['value'];
                }
            }
        }
        return 0;
    }

    public function setChancesBalance($type, $amount)
    {
        $chances_balance = json_decode($this->chances_balance, true);
        $changed = false;
        foreach ($chances_balance as &$item) {
            if ($type === $item['pool_id']) {
                $item['value'] = $amount;
                $changed = true;
            } else {
                $item['value'] = $item['value'];
            }

        }
        if (!$changed) {
            $chances_balance[] = ['pool_id' => $type, 'value' => $amount];
        }
        $this->chances_balance = $chances_balance;
        if ($this->isDirty('chances_balance')) {
            $this->save();
        }
    }


}
