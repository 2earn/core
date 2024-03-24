<?php

namespace Core\Models;

use Illuminate\Database\Eloquent\Model;

class FinancialRequest extends Model
{
    protected $table = 'financial_request';
    public $timestamps = false;
    protected $casts = [
        'amount' => 'decimal:2',
    ];
    public function details()
    {
        return $this->hasMany(detail_financial_request::class, 'numeroRequest', 'numeroReq');
    }
}
