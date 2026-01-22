<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class translatearabes extends Model
{
    protected $table = 'translatearabe';
    protected $fillable = [ 'name','value'];
    public $timestamps = true;
}
