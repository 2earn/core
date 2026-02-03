<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasAuditing;

class FinancialRequest extends Model
{
    use HasFactory, HasAuditing;

    protected $table = 'financial_request';
    protected $primaryKey = 'numeroReq';
    public $incrementing = false;
    protected $keyType = 'string';
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

    /**
     * Sender relation - link idSender (user idUser) to User model
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'idSender', 'idUser');
    }
}
