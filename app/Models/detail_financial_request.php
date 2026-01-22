<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasAuditing;

class detail_financial_request extends Model
{
    use HasAuditing;

    protected $table = 'detail_financial_request';
    public $timestamps = true;

    protected $fillable = [
        'numeroRequest',
        'idUser',
        'response',
        'dateResponse',
        'vu',
        'created_by',
        'updated_by',
    ];

    public function FinancialRequest()
    {
        return $this->belongsTo(FinancialRequest::class);
    }

    public function User()
    {
        return $this->belongsTo(User::class, 'idUser', 'idUser');
    }

}
