<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServeyQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
    ];

    public function serveyQuestions()
    {
        return $this->hasMany(ServeyQuestionChoice::class,'question_id');
    }
}
