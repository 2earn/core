<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasAuditing;

class detail_financial_request extends Model
{
    use HasFactory, HasAuditing;

    protected $table = 'detail_financial_request';
    protected $primaryKey = null;
    public $incrementing = false;
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
