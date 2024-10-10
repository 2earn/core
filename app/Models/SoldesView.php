<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoldesView extends Model
{
    use HasFactory;

    protected $table = 'soldes_view';

    protected $fillable = [
        'cash',
        'bfs',
        'db',
        't',
        'sms',
        'action',
    ];
}
