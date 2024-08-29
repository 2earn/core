<?php

namespace Core\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    public $primaryKey = 'idSETTINGS';
    protected $table = 'settings';
    public $timestamps = false;

}
