<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class action_historys extends Model
{
    use HasFactory;

    protected $table = 'action_history';
    public $timestamps = false;
}
