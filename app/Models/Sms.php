<?php

namespace App\Models;

use App\Traits\HasAuditing;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sms extends Model
{
    use HasFactory, HasAuditing;

    protected $fillable = [
        'message',
        'destination_number',
        'source_number',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
    ];


}
