<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyQuestionChoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'serveyQuestion_id',
        'title',
    ];

    public function Question()
    {
        return $this->belongsTo(SurveyQuestion::class, 'question_id');
    }
}
