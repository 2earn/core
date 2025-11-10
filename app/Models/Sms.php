<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasAuditing;

class Sms extends Model
{
    use HasFactory, HasAuditing;

    protected $fillable = [
        'message',
        'destination_number',
        'source_number',
        'created_by',
        'updated_by',
    ];
}
