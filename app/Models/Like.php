<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasAuditing;

class Like extends Model
{
    use HasFactory, HasAuditing;

    protected $fillable = [
        'user_id',
        'survey_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function likable()
    {
        return $this->morphTo();
    }
}
