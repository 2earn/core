<?php

namespace App\Observers;

use App\Models\Pool;

class PoolObserver
{
    public function sharePool(Pool $pool)
    {

    }

    public function init(Pool $pool)
    {
        $pool->value = $pool->value - $pool->max;
        $pool->save();
    }

    public function updated(Pool $pool)
    {
        if ($pool->value >= $pool->max) {
            $this->init($pool);
            $this->sharePool($pool);
        }
    }


}
