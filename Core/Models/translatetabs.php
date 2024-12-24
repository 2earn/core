<?php

namespace Core\Models;

use Illuminate\Database\Eloquent\Model;

class translatetabs extends Model
{
    protected $table = 'translatetab';

    protected $fillable = [
        'name',
        'value',
        'valueFr',
        'valueEn',
        'valueTr',
        'valueEs'
    ];

    public $timestamps = false;
}
