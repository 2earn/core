<?php

namespace Core\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Spatie\Translatable\HasTranslations;

class identificationuserrequest extends Model
{
    protected $table = 'identificationuserrequest';
    protected $fillable=[
        'idUser',
        'created_at',
        'updated_at',
        'response',
        'note',
        'status',
        'responseDate'
    ];
}
