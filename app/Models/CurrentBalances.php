<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasAuditing;

class CurrentBalances extends Model
{
    use HasFactory, HasAuditing;

    protected $fillable = [
        'idUser',
        'user_id',
        'amount',
        'value',
        'last_value',
        'created_by',
        'updated_by',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
