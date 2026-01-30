<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasAuditing;

class translatetabs extends Model
{
    use HasFactory, HasAuditing;

    protected $table = 'translatetab';

    protected $fillable = [
        'name',
        'value',
        'valueFr',
        'valueEn',
        'valueTr',
        'valueEs',
        'valueRu',
        'valueDe',
        'created_by',
        'updated_by',
    ];

    public $timestamps = true;
}
