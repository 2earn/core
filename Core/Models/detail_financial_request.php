<?php

namespace Core\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class detail_financial_request extends Model
{
    protected $table = 'detail_financial_request';
    public $timestamps = false;

    protected $fillable = [
        'response', 'dateResponse'
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
