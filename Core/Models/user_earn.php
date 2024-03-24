<?php

namespace Core\Models;

use Illuminate\Database\Eloquent\Model;

class user_earn extends Model
{
    public $timestamps = false;
    protected $table = 'user_earns';
    public function userbalances()
    {
        return $this->hasMany(user_balance::class,'idUser','idUser');
    }
}
