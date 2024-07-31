<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServeyQuestionChoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'serveyQuestion_id',
        'title',
    ];

    public function Question()
    {
        return $this->belongsTo(ServeyQuestion::class, 'question_id');
    }
}
