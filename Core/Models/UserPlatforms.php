<?php

namespace Core\Models;

use Illuminate\Database\Eloquent\Model;

class UserPlatforms extends Model
{
    protected $table = 'user_plateforme';
    public $timestamps = false ;
    protected $fillable = ['user_id','plateforme_id'];
}
