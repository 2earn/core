<?php

namespace Core\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasAuditing;

class FinancialRequest extends Model
{
    use HasAuditing;

    protected $table = 'financial_request';
    public $timestamps = true;

    protected $fillable = [
        'idSender',
        'date',
        'amount',
        'status',
        'idUserAccepted',
        'dateAccepted',
        'typeReq',
        'securityCode',
        'vu',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function details()
    {
        return $this->hasMany(detail_financial_request::class, 'numeroRequest', 'numeroReq');
    }
}
