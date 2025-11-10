<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasAuditing;

class Faq extends Model
{
    use HasFactory, HasAuditing;

    protected $fillable = [
        'question',
        'answer'
    ];
}
