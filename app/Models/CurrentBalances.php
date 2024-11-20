<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurrentBalances extends Model
{
    use HasFactory;

    protected $fillable = [
        'idUser',
        'user_id',
        'amount',
        'value',
        'last_value',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
