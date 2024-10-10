<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class vip extends Model
{
    use HasFactory;

    protected $table = 'vip';
    public $timestamps = false;
    protected $fillable = [
        'idUser',
        'flashCoefficient',
        'flashDeadline',
        'flashNote',
        'flashMinAmount',
        'dateFNS',
        'maxShares',
        'solde',
        'declenched',
        'declenchedDate',
        'closed',
        'closedDate',
    ];

}
