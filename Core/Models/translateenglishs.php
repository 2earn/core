<?php

namespace Core\Models;

use Illuminate\Database\Eloquent\Model;

class translateenglishs extends Model
{
    protected $table = 'translateenglish';
    protected $fillable = [ 'name','value'];
    public $timestamps = true;
}
