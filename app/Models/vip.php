<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasAuditing;

class vip extends Model
{
    use HasFactory, HasAuditing;

    protected $table = 'vip';
    public $timestamps = true;
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
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'closed' => 'boolean',
        'declenched' => 'boolean',
    ];

}
